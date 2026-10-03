<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MIL_AI_OpenAI {

	const TIMEOUT     = 240;
	const IMAGE_SIZES = '1024x1024,1536x1024,1024x1536,auto';

	private $api_key;
	private $base;
	private $text_model;
	private $image_model;
	private $org;

	private $last_usage = array();

	public function __construct() {
		$this->api_key     = trim( (string) MIL_Env::get( 'MIL_AI_API_KEY' ) );
		$this->base        = untrailingslashit( (string) MIL_Env::get( 'MIL_AI_BASE_URL', 'https://api.openai.com/v1' ) );
		$this->text_model  = (string) MIL_Env::get( 'MIL_AI_TEXT_MODEL', 'gpt-4o-mini' );
		$this->image_model = (string) MIL_Env::get( 'MIL_AI_IMAGE_MODEL', 'gpt-image-1' );
		$this->org         = trim( (string) MIL_Env::get( 'MIL_AI_ORG' ) );
	}

	public function configured() {
		return '' !== $this->api_key;
	}

	public function info() {
		return array(
			'base'        => $this->base,
			'text_model'  => $this->text_model,
			'image_model' => $this->image_model,
			'org'         => $this->org,
			'key_masked'  => MIL_Env::masked( 'MIL_AI_API_KEY' ),
			'env_file'    => MIL_Env::file(),
			'env_exists'  => is_readable( MIL_Env::file() ),
		);
	}

	public function usage() {
		return $this->last_usage;
	}

	private function headers() {
		$headers = array(
			'Authorization' => 'Bearer ' . $this->api_key,
			'Content-Type'  => 'application/json',
			'Accept'        => 'application/json',
		);

		if ( '' !== $this->org ) {
			$headers['OpenAI-Organization'] = $this->org;
		}

		$project = trim( (string) MIL_Env::get( 'MIL_AI_PROJECT' ) );

		if ( '' !== $project ) {
			$headers['OpenAI-Project'] = $project;
		}

		return $headers;
	}

	private function post( $endpoint, array $body ) {
		if ( ! $this->configured() ) {
			return new WP_Error( 'mil_ai_no_key', __( 'Falta configurar MIL_AI_API_KEY en el archivo .env del tema.', 'mil' ) );
		}

		$response = wp_remote_post(
			$this->base . $endpoint,
			array(
				'headers' => $this->headers(),
				'body'    => wp_json_encode( $body ),
				'timeout' => self::TIMEOUT,
			)
		);

		if ( is_wp_error( $response ) ) {
			return new WP_Error( 'mil_ai_network', $response->get_error_message() );
		}

		$code = (int) wp_remote_retrieve_response_code( $response );
		$raw  = wp_remote_retrieve_body( $response );

		$json = json_decode( $raw, true );

		if ( $code < 200 || $code >= 300 ) {
			return new WP_Error(
				'mil_ai_http_' . $code,
				$this->extract_error( $json, $raw ),
				array( 'status' => $code )
			);
		}

		if ( ! is_array( $json ) ) {
			return new WP_Error( 'mil_ai_bad_json', __( 'La API devolvio una respuesta ilegible.', 'mil' ) );
		}

		if ( isset( $json['usage'] ) && is_array( $json['usage'] ) ) {
			$this->last_usage = $json['usage'];
		}

		return $json;
	}

	private function extract_error( $json, $raw ) {
		if ( is_array( $json ) && isset( $json['error']['message'] ) ) {
			return (string) $json['error']['message'];
		}

		if ( is_array( $json ) && isset( $json['message'] ) ) {
			return (string) $json['message'];
		}

		return __( 'Error HTTP: ', 'mil' ) . mb_substr( wp_strip_all_tags( (string) $raw ), 0, 400 );
	}

	public function chat_json( $system, $user, array $options = array() ) {
		$body = array(
			'model'    => isset( $options['model'] ) ? $options['model'] : $this->text_model,
			'messages' => array(
				array(
					'role'    => 'system',
					'content' => $system,
				),
				array(
					'role'    => 'user',
					'content' => $user,
				),
			),
			'response_format' => array( 'type' => 'json_object' ),
		);

		if ( isset( $options['temperature'] ) ) {
			$body['temperature'] = (float) $options['temperature'];
		}

		if ( isset( $options['max_tokens'] ) ) {
			$body['max_tokens'] = (int) $options['max_tokens'];
		}

		$json = $this->post( '/chat/completions', $body );

		if ( is_wp_error( $json ) ) {
			return $json;
		}

		if ( empty( $json['choices'][0]['message']['content'] ) ) {
			return new WP_Error( 'mil_ai_empty', __( 'La API no devolvio contenido.', 'mil' ) );
		}

		$content = $this->strip_fences( $json['choices'][0]['message']['content'] );
		$data    = json_decode( $content, true );

		if ( ! is_array( $data ) ) {
			return new WP_Error(
				'mil_ai_parse',
				__( 'No se pudo interpretar el JSON devuelto por el modelo.', 'mil' ),
				array( 'raw' => mb_substr( $content, 0, 800 ) )
			);
		}

		return $data;
	}

	private function strip_fences( $text ) {
		$text = trim( (string) $text );
		$text = preg_replace( '/^```(?:json)?\s*/i', '', $text );
		$text = preg_replace( '/\s*```$/', '', $text );

		return trim( $text );
	}

	public function generate_image( $prompt, $size = '1024x1024' ) {
		$allowed = explode( ',', self::IMAGE_SIZES );
		$size    = in_array( $size, $allowed, true ) ? $size : '1024x1024';

		$body = array(
			'model'  => $this->image_model,
			'prompt' => (string) $prompt,
			'n'      => 1,
			'size'   => $size,
		);

		$json = $this->post( '/images/generations', $body );

		if ( is_wp_error( $json ) ) {
			return $json;
		}

		$item = isset( $json['data'][0] ) ? $json['data'][0] : null;

		if ( ! is_array( $item ) ) {
			return new WP_Error( 'mil_ai_no_image', __( 'La API no devolvio ninguna imagen.', 'mil' ) );
		}

		if ( ! empty( $item['b64_json'] ) ) {
			$bytes = base64_decode( $item['b64_json'], true );

			if ( false === $bytes || '' === $bytes ) {
				return new WP_Error( 'mil_ai_bad_image', __( 'La imagen devuelta no es valida.', 'mil' ) );
			}

			return array(
				'bytes' => $bytes,
				'mime'  => ! empty( $item['output_format'] ) ? 'image/' . $item['output_format'] : 'image/png',
			);
		}

		if ( ! empty( $item['url'] ) ) {
			$download = wp_remote_get( $item['url'], array( 'timeout' => self::TIMEOUT ) );

			if ( is_wp_error( $download ) ) {
				return $download;
			}

			$body   = wp_remote_retrieve_body( $download );
			$ctype  = wp_remote_retrieve_header( $download, 'content-type' );
			$ctype  = is_array( $ctype ) ? current( $ctype ) : $ctype;

			if ( empty( $body ) ) {
				return new WP_Error( 'mil_ai_image_empty', __( 'La imagen descargada esta vacia.', 'mil' ) );
			}

			return array(
				'bytes' => $body,
				'mime'  => $ctype ? $ctype : 'image/png',
			);
		}

		return new WP_Error( 'mil_ai_no_image', __( 'Formato de imagen desconocido.', 'mil' ) );
	}
}
