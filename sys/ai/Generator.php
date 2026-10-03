<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MIL_AI_Generator {

	const HISTORY_OPTION = 'mil_ai_history';
	const HISTORY_LIMIT  = 50;

	private $ai;

	public function __construct( MIL_AI_OpenAI $ai ) {
		$this->ai = $ai;
	}

	public static function sanitize_params( array $raw ) {
		$clean = array();

		$clean['nicho']          = self::text( $raw, 'nicho', get_bloginfo( 'name' ) );
		$clean['tema']           = self::text( $raw, 'tema', $clean['nicho'] );
		$clean['audiencia']      = self::text( $raw, 'audiencia', __( 'Personas adultas interessadas en el tema.', 'mil' ) );
		$clean['objetivo']       = self::text( $raw, 'objetivo', 'tráfico orgánico y autoridad' );
		$clean['keywords']       = self::text( $raw, 'keywords' );
		$clean['extra']          = self::text( $raw, 'extra' );
		$clean['referencia']     = self::text( $raw, 'referencia' );
		$clean['restricciones']  = self::text( $raw, 'restricciones' );
		$clean['cta']            = self::text( $raw, 'cta' );

		$clean['tono']       = self::choice( $raw, 'tono', array( 'profesional', 'cercano', 'didactico', 'divulgativo', 'argumentativo' ), 'profesional' );
		$clean['longitud']   = self::choice( $raw, 'longitud', array( 'corto', 'medio', 'largo', 'completo' ), 'medio' );
		$clean['estructura'] = self::choice( $raw, 'estructura', array( 'libre', 'paso a paso', 'comparativa', 'preguntas frecuentes', 'casos reales' ), 'libre' );
		$clean['idioma']     = self::choice( $raw, 'idioma', array( 'es', 'en' ), 'es' );
		$clean['estilo_imagen'] = self::choice( $raw, 'estilo_imagen', array( 'editorial', 'ilustracion plana', 'fotografia', 'minimalista', 'infografia' ), 'editorial' );
		$clean['tamano_imagen'] = self::choice( $raw, 'tamano_imagen', array( '1536x1024', '1024x1024', '1024x1536' ), '1536x1024' );

		$cantidad = isset( $raw['cantidad'] ) ? (int) $raw['cantidad'] : 5;
		$clean['cantidad'] = max( 1, min( 12, $cantidad ) );

		$clean['usar_contexto']    = ! empty( $raw['usar_contexto'] ) ? '1' : '';
		$clean['con_imagen']       = ! empty( $raw['con_imagen'] ) ? '1' : '';
		$clean['publicar']         = ! empty( $raw['publicar'] ) ? '1' : '';
		$clean['sobrescribir_tags'] = ! empty( $raw['sobrescribir_tags'] ) ? '1' : '';

		$categorias = array();

		if ( isset( $raw['categorias'] ) ) {
			$categorias = is_array( $raw['categorias'] ) ? $raw['categorias'] : explode( ',', (string) $raw['categorias'] );
		}

		$clean['categorias'] = array_values( array_filter( array_map( 'sanitize_text_field', $categorias ) ) );

		return $clean;
	}

	private static function text( array $raw, $key, $default = '' ) {
		if ( ! isset( $raw[ $key ] ) || ! is_scalar( $raw[ $key ] ) ) {
			return $default;
		}

		$value = sanitize_textarea_field( (string) $raw[ $key ] );

		return '' === $value ? $default : $value;
	}

	private static function choice( array $raw, $key, array $allowed, $default ) {
		if ( ! isset( $raw[ $key ] ) || ! is_scalar( $raw[ $key ] ) ) {
			return $default;
		}

		$value = sanitize_key( (string) $raw[ $key ] );

		return in_array( $value, $allowed, true ) ? $value : $default;
	}

	public function propose( array $p ) {
		$data = $this->ai->chat_json(
			MIL_AI_Prompt::ideas_system(),
			MIL_AI_Prompt::ideas_user( $p ),
			array(
				'temperature' => 0.9,
				'max_tokens'  => 4000,
			)
		);

		if ( is_wp_error( $data ) ) {
			return $data;
		}

		$ideas = array();

		foreach ( (array) ( isset( $data['propuestas'] ) ? $data['propuestas'] : array() ) as $item ) {
			if ( ! is_array( $item ) || empty( $item['titulo'] ) ) {
				continue;
			}

			$ideas[] = array(
				'id'        => wp_generate_password( 8, false, false ),
				'titulo'    => sanitize_text_field( $item['titulo'] ),
				'subtitulo' => isset( $item['subtitulo'] ) ? sanitize_text_field( $item['subtitulo'] ) : '',
				'angulo'    => isset( $item['angulo'] ) ? sanitize_textarea_field( $item['angulo'] ) : '',
				'hook'      => isset( $item['hook'] ) ? sanitize_textarea_field( $item['hook'] ) : '',
				'keywords'  => isset( $item['keywords'] ) ? array_values( array_map( 'sanitize_text_field', (array) $item['keywords'] ) ) : array(),
				'categoria' => isset( $item['categoria'] ) ? sanitize_text_field( $item['categoria'] ) : '',
				'nivel'     => isset( $item['nivel'] ) ? sanitize_key( $item['nivel'] ) : 'medio',
				'razon'     => isset( $item['razon'] ) ? sanitize_textarea_field( $item['razon'] ) : '',
				'esqueleto' => isset( $item['esqueleto'] ) ? array_values( array_map( 'sanitize_text_field', (array) $item['esqueleto'] ) ) : array(),
			);
		}

		if ( ! $ideas ) {
			return new WP_Error( 'mil_ai_no_ideas', __( 'El modelo no devolvio ninguna propuesta valida.', 'mil' ) );
		}

		return $ideas;
	}

	public function write( array $p, array $idea ) {
		$data = $this->ai->chat_json(
			MIL_AI_Prompt::article_system(),
			MIL_AI_Prompt::article_user( $p, $idea ),
			array(
				'temperature' => 0.7,
				'max_tokens'  => 8000,
			)
		);

		if ( is_wp_error( $data ) ) {
			return $data;
		}

		if ( empty( $data['titulo'] ) || empty( $data['contenido_html'] ) ) {
			return new WP_Error( 'mil_ai_incomplete', __( 'El modelo devolvio un articulo incompleto.', 'mil' ) );
		}

		return $this->normalize_article( $data );
	}

	private function normalize_article( array $data ) {
		$title = sanitize_text_field( $data['titulo'] );

		$slug = isset( $data['slug'] ) ? sanitize_title( $data['slug'] ) : '';

		if ( '' === $slug ) {
			$slug = sanitize_title( $title );
		}

		if ( '' === $slug ) {
			$slug = 'articulo-' . wp_generate_password( 6, false, false );
		}

		$slug = wp_unique_post_slug( $slug, 0, null, 'post', 0 );

		$content = $data['contenido_html'];
		$content = preg_replace( '#<(script|style|iframe|object|embed)\b[^>]*>.*?</\1>#is', '', $content );
		$content = preg_replace( '#<(script|style|iframe|object|embed)\b[^>]*/?>#is', '', $content );
		$content = preg_replace( '#\s(style|on[a-z]+)\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)#i', '', $content );
		$content = wp_kses_post( $content );
		$content = trim( $content );

		$keywords = isset( $data['keywords'] ) ? (array) $data['keywords'] : array();

		return array(
			'titulo'           => $title,
			'subtitulo'        => isset( $data['subtitulo'] ) ? sanitize_text_field( $data['subtitulo'] ) : '',
			'slug'             => $slug,
			'sumilla'          => isset( $data['sumilla'] ) ? sanitize_textarea_field( $data['sumilla'] ) : '',
			'contenido_html'   => $content,
			'keywords'         => array_values( array_filter( array_map( 'sanitize_text_field', $keywords ) ) ),
			'meta_description' => isset( $data['meta_description'] ) ? sanitize_textarea_field( $data['meta_description'] ) : '',
			'faq'              => $this->normalize_faq( isset( $data['faq'] ) ? $data['faq'] : array() ),
			'imagen_prompt'    => isset( $data['imagen_prompt'] ) ? sanitize_textarea_field( $data['imagen_prompt'] ) : '',
			'notas_verificacion' => isset( $data['notas_verificacion'] ) ? sanitize_textarea_field( $data['notas_verificacion'] ) : '',
			'palabras'         => max( 1, (int) str_word_count( wp_strip_all_tags( $content ) ) ),
		);
	}

	private function normalize_faq( $faq ) {
		$out = array();

		foreach ( (array) $faq as $item ) {
			if ( ! is_array( $item ) || empty( $item['pregunta'] ) ) {
				continue;
			}

			$out[] = array(
				'pregunta' => sanitize_text_field( $item['pregunta'] ),
				'respuesta' => isset( $item['respuesta'] ) ? sanitize_textarea_field( $item['respuesta'] ) : '',
			);
		}

		return $out;
	}

	public function make_image( array $p, array $article ) {
		$prompt = MIL_AI_Prompt::image_user( $p, $article );

		$result = $this->ai->generate_image( $prompt, $p['tamano_imagen'] );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		$attachment_id = $this->store_image( $article['titulo'], $result['bytes'], $result['mime'] );

		if ( is_wp_error( $attachment_id ) ) {
			return $attachment_id;
		}

		return $attachment_id;
	}

	private function store_image( $title, $bytes, $mime ) {
		$ext = 'image/png';

		if ( preg_match( '#image/(jpeg|jpg|png|webp)#', (string) $mime, $m ) ) {
			$ext = 'jpg' === $m[1] ? 'jpg' : $m[1];
		}

		$name = sanitize_file_name( remove_accents( $title ) );

		if ( '' === $name ) {
			$name = 'mil-ai';
		}

		$name = substr( $name, 0, 60 ) . '-' . wp_generate_password( 6, false, false ) . '.' . $ext;

		$upload = wp_upload_bits( $name, null, $bytes );

		if ( ! empty( $upload['error'] ) ) {
			return new WP_Error( 'mil_ai_upload', $upload['error'] );
		}

		$filetype = wp_check_filetype( $upload['file'], null );

		$attachment_id = wp_insert_attachment(
			array(
				'post_mime_type' => $filetype['type'] ? $filetype['type'] : 'image/png',
				'post_title'     => $title,
				'post_content'   => '',
				'post_status'    => 'inherit',
			),
			$upload['file']
		);

		if ( is_wp_error( $attachment_id ) ) {
			return $attachment_id;
		}

		require_once ABSPATH . 'wp-admin/includes/image.php';

		$meta = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );

		if ( $meta ) {
			wp_update_attachment_metadata( $attachment_id, $meta );
		}

		update_post_meta( $attachment_id, '_mil_ai_generated', 1 );

		return (int) $attachment_id;
	}

	public function save( array $p, array $article, array $idea, $attachment_id = 0 ) {
		$status = ! empty( $p['publicar'] ) ? 'publish' : 'draft';
		$author = self::resolve_author();

		$postarr = array(
			'post_type'    => 'post',
			'post_status'  => $status,
			'post_title'   => $article['titulo'],
			'post_name'    => $article['slug'],
			'post_content' => $article['contenido_html'],
			'post_excerpt' => $article['sumilla'],
			'post_author'  => $author,
		);

		$post_id = wp_insert_post( wp_slash( $postarr ), true );

		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		if ( $attachment_id && 'attachment' === get_post_type( $attachment_id ) ) {
			set_post_thumbnail( $post_id, (int) $attachment_id );
		}

		$tags = isset( $idea['keywords'] ) && is_array( $idea['keywords'] ) ? $idea['keywords'] : array();

		if ( ! empty( $p['sobrescribir_tags'] ) || ! $tags ) {
			$tags = $article['keywords'];
		}

		if ( ! empty( $tags ) ) {
			wp_set_post_terms( $post_id, $tags, 'post_tag', false );
		}

		$categories = array();

		if ( ! empty( $idea['categoria'] ) ) {
			$categories[] = $idea['categoria'];
		}

		$categories = array_merge( $categories, (array) $p['categorias'] );
		$categories = self::resolve_terms( $categories, 'category' );

		if ( $categories ) {
			wp_set_post_categories( $post_id, $categories, false );
		}

		update_post_meta( $post_id, '_mil_ai_generated', 1 );
		update_post_meta( $post_id, '_mil_ai_model', $this->ai->info()['text_model'] );
		update_post_meta( $post_id, '_mil_ai_meta_description', $article['meta_description'] );
		update_post_meta( $post_id, '_mil_ai_faq', $article['faq'] );
		update_post_meta( $post_id, '_mil_ai_notas', $article['notas_verificacion'] );

		if ( ! empty( $idea['angulo'] ) ) {
			update_post_meta( $post_id, '_mil_ai_angulo', $idea['angulo'] );
		}

		self::log_history(
			array(
				'post_id' => $post_id,
				'titulo'  => $article['titulo'],
				'slug'    => $article['slug'],
				'status'  => $status,
				'imagen'  => (int) $attachment_id,
				'palabras' => $article['palabras'],
				'fecha'   => current_time( 'mysql' ),
			)
		);

		return (int) $post_id;
	}

	public static function history() {
		$history = get_option( self::HISTORY_OPTION, array() );

		return is_array( $history ) ? $history : array();
	}

	private static function resolve_author() {
		$author = get_current_user_id();

		if ( $author > 0 ) {
			return $author;
		}

		$admins = get_users(
			array(
				'role'   => 'administrator',
				'number' => 1,
				'fields' => 'ID',
			)
		);

		return $admins ? (int) $admins[0] : 0;
	}

	private static function resolve_terms( array $names, $taxonomy ) {
		$ids = array();

		foreach ( $names as $name ) {
			$name = trim( (string) $name );

			if ( '' === $name ) {
				continue;
			}

			$term = get_term_by( 'name', $name, $taxonomy );

			if ( ! $term ) {
				$term = get_term_by( 'slug', sanitize_title( $name ), $taxonomy );
			}

			if ( ! $term ) {
				$created = wp_insert_term( $name, $taxonomy );

				if ( is_wp_error( $created ) ) {
					$existing = $created->get_error_data( 'term_exists' );

					if ( $existing ) {
						$ids[] = (int) $existing;
						continue;
					}

					continue;
				}

				$ids[] = (int) $created['term_id'];
				continue;
			}

			$ids[] = (int) $term->term_id;
		}

		return array_values( array_unique( array_filter( $ids ) ) );
	}

	private static function log_history( array $entry ) {
		$history = self::history();

		array_unshift( $history, $entry );

		if ( count( $history ) > self::HISTORY_LIMIT ) {
			$history = array_slice( $history, 0, self::HISTORY_LIMIT );
		}

		update_option( self::HISTORY_OPTION, $history, false );
	}
}
