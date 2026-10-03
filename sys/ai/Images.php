<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MIL_AI_Images {

	const STOPWORDS = array(
		'de', 'la', 'el', 'los', 'las', 'un', 'una', 'unos', 'unas', 'y', 'o', 'u',
		'que', 'en', 'con', 'por', 'para', 'del', 'al', 'se', 'sin', 'sobre', 'como',
		'cuando', 'donde', 'cual', 'cuales', 'como', 'mas', 'muy', 'este', 'esta',
		'estos', 'estas', 'eso', 'esa', 'es', 'son', 'ser', 'hay', 'no', 'si', 'su',
		'tu', 'mi', 'lo', 'le', 'les', 'desde', 'hasta', 'entre', 'tras', 'segun',
	);

	public static function missing_count() {
		global $wpdb;

		return (int) $wpdb->get_var(
			"SELECT COUNT(ID)
			 FROM {$wpdb->posts} p
			 WHERE p.post_type = 'post'
			   AND p.post_status = 'publish'
			   AND NOT EXISTS (
			     SELECT 1 FROM {$wpdb->postmeta} m
			     WHERE m.post_id = p.ID AND m.meta_key = '_thumbnail_id'
			   )"
		);
	}

	public static function missing( $limit = 10 ) {
		global $wpdb;

		$limit = max( 1, min( 50, (int) $limit ) );

		$ids = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT p.ID
				 FROM {$wpdb->posts} p
				 WHERE p.post_type = 'post'
				   AND p.post_status = 'publish'
				   AND NOT EXISTS (
				     SELECT 1 FROM {$wpdb->postmeta} m
				     WHERE m.post_id = p.ID AND m.meta_key = '_thumbnail_id'
				   )
				 ORDER BY p.post_date DESC
				 LIMIT %d",
				$limit
			)
		);

		return array_map( 'absint', (array) $ids );
	}

	public static function query_for_post( $post_id ) {
		$queries = self::queries_for_post( $post_id );

		return $queries ? $queries[0] : '';
	}

	/**
	 * Busquedas alternativas, de la mas especifica a la mas amplia.
	 * Openverse y Commons exigen todos los terminos, asi que una sola
	 * consulta larga casi siempre da 0 resultados.
	 */
	public static function queries_for_post( $post_id ) {
		$post = get_post( $post_id );

		if ( ! $post ) {
			return array();
		}

		$terms = mil_post_terms( $post_id, 1 );
		$cat   = $terms ? remove_accents( $terms[0]->name ) : '';
		$words = preg_split( '/\s+/', remove_accents( wp_strip_all_tags( $post->post_title ) ) );

		$keywords = array();

		foreach ( (array) $words as $word ) {
			$word = strtolower( trim( $word, " \t\n\r\0\x0B.,;:¿?¡!\"'()[]{}" ) );

			if ( '' === $word || in_array( $word, self::STOPWORDS, true ) || strlen( $word ) < 3 ) {
				continue;
			}

			if ( ! in_array( $word, $keywords, true ) ) {
				$keywords[] = $word;
			}

			if ( count( $keywords ) >= 6 ) {
				break;
			}
		}

		$queries = array();

		$add = function ( $q ) use ( &$queries ) {
			$q = trim( preg_replace( '/\s+/', ' ', (string) $q ) );

			if ( '' !== $q && ! in_array( $q, $queries, true ) ) {
				$queries[] = $q;
			}
		};

		$kw1 = isset( $keywords[0] ) ? $keywords[0] : '';
		$kw2 = isset( $keywords[1] ) ? $keywords[1] : '';

		$add( trim( $cat . ' ' . $kw1 ) );       // categoria + palabra clave
		$add( trim( $kw1 . ' ' . $kw2 ) );       // dos palabras clave
		$add( $kw1 );                            // palabra representativa sola
		$add( $cat );                            // categoria sola
		$add( $kw2 );                            // segunda palabra
		$add( implode( ' ', $keywords ) );       // todas las palabras
		$add( remove_accents( wp_strip_all_tags( $post->post_title ) ) ); // titulo completo

		return $queries;
	}

	public static function search( $query, $page = 1 ) {
		$pexels_key = MIL_Env::get( 'MIL_PEXELS_API_KEY' );

		if ( '' !== $pexels_key ) {
			$result = self::pexels( $pexels_key, $query );

			if ( ! is_wp_error( $result ) && $result ) {
				return $result;
			}
		}

		$result = self::openverse( $query, $page );

		if ( ! is_wp_error( $result ) && $result ) {
			return $result;
		}

		return self::commons( $query );
	}

	private static function openverse( $query, $page = 1 ) {
		$url = add_query_arg(
			array(
				'q'            => rawurlencode( $query ),
				'license_type' => 'commercial',
				'page_size'    => 6,
				'page'         => max( 1, (int) $page ),
				'mature'       => 'false',
				'format'       => 'json',
			),
			'https://api.openverse.org/v1/images/'
		);

		$response = wp_remote_get( $url, array( 'timeout' => 20 ) );

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return new WP_Error( 'mil_images_openverse', __( 'Openverse no respondio.', 'mil' ) );
		}

		$data = json_decode( wp_remote_retrieve_body( $response ), true );

		$out = array();

		foreach ( (array) ( isset( $data['results'] ) ? $data['results'] : array() ) as $item ) {
			if ( empty( $item['url'] ) ) {
				continue;
			}

			$out[] = array(
				'url'      => esc_url_raw( $item['url'] ),
				'title'    => isset( $item['title'] ) ? sanitize_text_field( $item['title'] ) : '',
				'provider' => 'openverse',
				'author'   => isset( $item['creator'] ) ? sanitize_text_field( $item['creator'] ) : '',
				'license'  => isset( $item['license'] ) ? sanitize_text_field( strtoupper( $item['license'] ) . ' ' . ( isset( $item['license_version'] ) ? $item['license_version'] : '' ) ) : '',
				'source'   => isset( $item['foreign_landing_url'] ) ? esc_url_raw( $item['foreign_landing_url'] ) : '',
			);
		}

		return $out;
	}

	private static function pexels( $key, $query ) {
		$url = add_query_arg(
			array(
				'query'      => rawurlencode( $query ),
				'per_page'   => 6,
				'orientation' => 'landscape',
			),
			'https://api.pexels.com/v1/search'
		);

		$response = wp_remote_get(
			$url,
			array(
				'timeout' => 20,
				'headers' => array( 'Authorization' => $key ),
			)
		);

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return new WP_Error( 'mil_images_pexels', __( 'Pexels no respondio.', 'mil' ) );
		}

		$data = json_decode( wp_remote_retrieve_body( $response ), true );

		$out = array();

		foreach ( (array) ( isset( $data['photos'] ) ? $data['photos'] : array() ) as $photo ) {
			$src = isset( $photo['src']['large'] ) ? $photo['src']['large'] : ( isset( $photo['src']['original'] ) ? $photo['src']['original'] : '' );

			if ( ! $src ) {
				continue;
			}

			$out[] = array(
				'url'      => esc_url_raw( $src ),
				'title'    => isset( $photo['alt'] ) ? sanitize_text_field( $photo['alt'] ) : '',
				'provider' => 'pexels',
				'author'   => isset( $photo['photographer'] ) ? sanitize_text_field( $photo['photographer'] ) : '',
				'license'  => __( 'Licencia Pexels (uso libre)', 'mil' ),
				'source'   => isset( $photo['url'] ) ? esc_url_raw( $photo['url'] ) : '',
			);
		}

		return $out;
	}

	private static function commons( $query ) {
		$url = add_query_arg(
			array(
				'action'     => 'query',
				'generator'  => 'search',
				'gsrsearch'  => rawurlencode( 'filetype:bitmap ' . $query ),
				'gsrnamespace' => 6,
				'gsrlimit'   => 6,
				'prop'       => 'imageinfo',
				'iiprop'     => 'url|extmetadata',
				'iiurlwidth' => 1280,
				'format'     => 'json',
			),
			'https://commons.wikimedia.org/w/api.php'
		);

		$response = wp_remote_get(
			$url,
			array(
				'timeout' => 20,
				'headers' => array( 'User-Agent' => 'MilDolares/1.0 (WordPress theme image fetcher)' ),
			)
		);

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return new WP_Error( 'mil_images_commons', __( 'Wikimedia Commons no respondio.', 'mil' ) );
		}

		$data = json_decode( wp_remote_retrieve_body( $response ), true );

		$pages = isset( $data['query']['pages'] ) ? $data['query']['pages'] : array();
		$out   = array();

		foreach ( (array) $pages as $page_item ) {
			if ( empty( $page_item['imageinfo'][0] ) ) {
				continue;
			}

			$info   = $page_item['imageinfo'][0];
			$src    = ! empty( $info['thumburl'] ) ? $info['thumburl'] : ( isset( $info['url'] ) ? $info['url'] : '' );
			$meta   = isset( $info['extmetadata'] ) ? $info['extmetadata'] : array();
			$author = isset( $meta['Artist']['value'] ) ? wp_strip_all_tags( $meta['Artist']['value'] ) : '';
			$lic    = isset( $meta['LicenseShortName']['value'] ) ? wp_strip_all_tags( $meta['LicenseShortName']['value'] ) : '';

			if ( ! $src ) {
				continue;
			}

			$out[] = array(
				'url'      => esc_url_raw( $src ),
				'title'    => sanitize_text_field( isset( $page_item['title'] ) ? $page_item['title'] : '' ),
				'provider' => 'commons',
				'author'   => sanitize_text_field( $author ),
				'license'  => sanitize_text_field( $lic ),
				'source'   => isset( $info['descriptionurl'] ) ? esc_url_raw( $info['descriptionurl'] ) : '',
			);
		}

		return $out;
	}

	public static function attach( $post_id ) {
		$post_id = absint( $post_id );

		if ( ! $post_id || has_post_thumbnail( $post_id ) ) {
			return new WP_Error( 'mil_images_skip', __( 'El post ya tiene imagen o no existe.', 'mil' ) );
		}

		$queries = self::queries_for_post( $post_id );

		if ( ! $queries ) {
			return new WP_Error( 'mil_images_query', __( 'No se pudo construir la busqueda.', 'mil' ) );
		}

		$candidates = array();
		$query_used = '';
		$tried      = array();

		foreach ( $queries as $query ) {
			$tried[] = $query;

			$result = self::search( $query );

			if ( is_wp_error( $result ) || ! $result ) {
				continue;
			}

			$candidates = $result;
			$query_used = $query;
			break;
		}

		if ( ! $candidates ) {
			return new WP_Error(
				'mil_images_empty',
				__( 'Sin resultados para: ', 'mil' ) . implode( ' | ', array_slice( $tried, 0, 4 ) )
			);
		}

		foreach ( $candidates as $candidate ) {
			$attachment_id = self::download( $candidate, get_the_title( $post_id ) );

			if ( ! is_wp_error( $attachment_id ) ) {
				set_post_thumbnail( $post_id, (int) $attachment_id );

				update_post_meta( (int) $attachment_id, '_mil_image_source', $candidate['provider'] );
				update_post_meta( (int) $attachment_id, '_mil_image_attribution', $candidate['author'] . ' | ' . $candidate['license'] . ' | ' . $candidate['source'] );
				update_post_meta( $post_id, '_mil_image_query', $query_used );

				return (int) $attachment_id;
			}
		}

		return new WP_Error( 'mil_images_download', __( 'No se pudo descargar ninguna imagen valida.', 'mil' ) );
	}

	private static function download( array $candidate, $title ) {
		$url = $candidate['url'];

		$response = wp_safe_remote_get(
			$url,
			array(
				'timeout' => 30,
				'headers' => array( 'User-Agent' => 'MilDolares/1.0 (WordPress theme image fetcher)' ),
			)
		);

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return new WP_Error( 'mil_images_http', __( 'Fallo la descarga.', 'mil' ) );
		}

		$bytes = wp_remote_retrieve_body( $response );

		if ( strlen( $bytes ) < 1024 || strlen( $bytes ) > 6 * MB_IN_BYTES ) {
			return new WP_Error( 'mil_images_size', __( 'Imagen fuera de tamano valido.', 'mil' ) );
		}

		$content_type = strtolower( wp_remote_retrieve_header( $response, 'content-type' ) );
		$ext_map      = array(
			'image/jpeg' => 'jpg',
			'image/png'  => 'png',
			'image/webp' => 'webp',
			'image/gif'  => 'gif',
		);

		$ext = '';

		if ( isset( $ext_map[ $content_type ] ) ) {
			$ext = $ext_map[ $content_type ];
		} else {
			$path = (string) wp_parse_url( $url, PHP_URL_PATH );
			$check = wp_check_filetype( $path );

			if ( ! empty( $check['ext'] ) ) {
				$ext = $check['ext'];
			}
		}

		if ( '' === $ext ) {
			return new WP_Error( 'mil_images_type', __( 'Tipo de archivo no soportado.', 'mil' ) );
		}

		$name = sanitize_file_name( remove_accents( $title ) . '-' . wp_generate_password( 6, false, false ) . '.' . $ext );

		$upload = wp_upload_bits( $name, null, $bytes );

		if ( ! empty( $upload['error'] ) ) {
			return new WP_Error( 'mil_images_upload', $upload['error'] );
		}

		$attachment = array(
			'post_mime_type' => 'image/' . ( 'jpg' === $ext ? 'jpeg' : $ext ),
			'post_title'     => sanitize_text_field( $title ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		$attachment_id = wp_insert_attachment( $attachment, $upload['file'], 0 );

		if ( is_wp_error( $attachment_id ) ) {
			return $attachment_id;
		}

		require_once ABSPATH . 'wp-admin/includes/image.php';

		$meta = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );

		if ( $meta ) {
			wp_update_attachment_metadata( $attachment_id, $meta );
		}

		return (int) $attachment_id;
	}
}
