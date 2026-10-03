<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MIL_AI_Admin {

	const SLUG        = 'mil-ia';
	const NONCE       = 'mil_ai_nonce';
	const CAPABILITY  = 'edit_posts';
	const BATCH_LIMIT = 10;

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		add_action( 'admin_menu', array( $this, 'menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );

		add_action( 'wp_ajax_mil_ai_propose', array( $this, 'ajax_propose' ) );
		add_action( 'wp_ajax_mil_ai_write', array( $this, 'ajax_write' ) );
		add_action( 'wp_ajax_mil_ai_save', array( $this, 'ajax_save' ) );
	}

	public function menu() {
		add_menu_page(
			__( 'MIL IA', 'mil' ),
			__( 'MIL IA', 'mil' ),
			self::CAPABILITY,
			self::SLUG,
			array( $this, 'render' ),
			'dashicons-superhero-alt',
			3
		);
	}

	public function assets( $hook ) {
		if ( 'toplevel_page_' . self::SLUG !== $hook ) {
			return;
		}

		$base = get_template_directory_uri() . '/sys/ai/assets';

		wp_enqueue_style( 'mil-ai-admin', $base . '/admin.css', array(), MIL_AI_VERSION );

		wp_enqueue_script( 'mil-ai-admin', $base . '/admin.js', array(), MIL_AI_VERSION, true );

		wp_localize_script(
			'mil-ai-admin',
			'MIL_AI',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( self::NONCE ),
				'i18n'    => array(
					'generando'   => __( 'Generando...', 'mil' ),
					'creandoImg'  => __( 'Creando imagen...', 'mil' ),
					'guardando'   => __( 'Guardando...', 'mil' ),
					'sinImagen'   => __( 'La imagen no se pudo generar.', 'mil' ),
					'confirmar'   => __( 'Se creara un borrador en el sitio. Continuar?', 'mil' ),
					'vacio'       => __( 'Escribe un tema y pulsa Proponer articulos.', 'mil' ),
					'selecciona'  => __( 'Selecciona al menos una propuesta.', 'mil' ),
				),
			)
		);
	}

	public function render() {
		if ( ! current_user_can( self::CAPABILITY ) ) {
			wp_die( esc_html__( 'No tienes permisos para acceder a esta pantalla.', 'mil' ) );
		}

		$ai         = new MIL_AI_OpenAI();
		$info       = $ai->info();
		$generator  = new MIL_AI_Generator( $ai );
		$history    = MIL_AI_Generator::history();
		$categories = get_categories( array( 'hide_empty' => false ) );

		include get_template_directory() . '/sys/views/ai/admin_page.php';
	}

	private function guard() {
		check_ajax_referer( self::NONCE, 'nonce' );

		if ( ! current_user_can( self::CAPABILITY ) ) {
			wp_send_json_error( array( 'message' => __( 'Permisos insuficientes.', 'mil' ) ), 403 );
		}

		if ( ! isset( $_POST['params'] ) || ! is_string( $_POST['params'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Faltan los parametros de generacion.', 'mil' ) ), 400 );
		}

		$raw = json_decode( wp_unslash( $_POST['params'] ), true );

		if ( ! is_array( $raw ) ) {
			wp_send_json_error( array( 'message' => __( 'Parametros no validos.', 'mil' ) ), 400 );
		}

		$params = MIL_AI_Generator::sanitize_params( $raw );

		if ( '' === trim( (string) $params['tema'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Indica el tema central del articulo.', 'mil' ) ), 400 );
		}

		$ai = new MIL_AI_OpenAI();

		if ( ! $ai->configured() ) {
			wp_send_json_error( array( 'message' => __( 'Falta configurar MIL_AI_API_KEY en el archivo .env del tema.', 'mil' ) ), 400 );
		}

		return array(
			'params' => $params,
			'ai'     => $ai,
			'gen'    => new MIL_AI_Generator( $ai ),
		);
	}

	public function ajax_propose() {
		$ctx = $this->guard();

		$result = $ctx['gen']->propose( $ctx['params'] );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ), 502 );
		}

		wp_send_json_success( array( 'ideas' => $result ) );
	}

	public function ajax_write() {
		$ctx = $this->guard();

		$idea_raw = isset( $_POST['idea'] ) ? wp_unslash( $_POST['idea'] ) : array();

		if ( is_string( $idea_raw ) ) {
			$idea_raw = json_decode( $idea_raw, true );
		}

		if ( ! is_array( $idea_raw ) || empty( $idea_raw['titulo'] ) ) {
			wp_send_json_error( array( 'message' => __( 'La propuesta no es valida.', 'mil' ) ), 400 );
		}

		$idea = array(
			'titulo'    => sanitize_text_field( $idea_raw['titulo'] ),
			'subtitulo' => isset( $idea_raw['subtitulo'] ) ? sanitize_text_field( $idea_raw['subtitulo'] ) : '',
			'angulo'    => isset( $idea_raw['angulo'] ) ? sanitize_textarea_field( $idea_raw['angulo'] ) : '',
			'hook'      => isset( $idea_raw['hook'] ) ? sanitize_textarea_field( $idea_raw['hook'] ) : '',
			'keywords'  => isset( $idea_raw['keywords'] ) ? array_values( array_map( 'sanitize_text_field', (array) $idea_raw['keywords'] ) ) : array(),
			'categoria' => isset( $idea_raw['categoria'] ) ? sanitize_text_field( $idea_raw['categoria'] ) : '',
			'esqueleto' => isset( $idea_raw['esqueleto'] ) ? array_values( array_map( 'sanitize_text_field', (array) $idea_raw['esqueleto'] ) ) : array(),
		);

		$article = $ctx['gen']->write( $ctx['params'], $idea );

		if ( is_wp_error( $article ) ) {
			wp_send_json_error( array( 'message' => $article->get_error_message() ), 502 );
		}

		$payload = array(
			'idea'    => $idea,
			'article' => $article,
			'image'   => array( 'id' => 0, 'url' => '' ),
		);

		if ( $ctx['params']['con_imagen'] ) {
			$attachment_id = $ctx['gen']->make_image( $ctx['params'], $article );

			if ( is_wp_error( $attachment_id ) ) {
				$payload['image_error'] = $attachment_id->get_error_message();
			} else {
				$payload['image'] = array(
					'id'  => (int) $attachment_id,
					'url' => wp_get_attachment_image_url( (int) $attachment_id, 'medium' ),
				);
			}
		}

		wp_send_json_success( $payload );
	}

	public function ajax_save() {
		$ctx = $this->guard();

		$payload = isset( $_POST['payload'] ) ? wp_unslash( $_POST['payload'] ) : array();

		if ( is_string( $payload ) ) {
			$payload = json_decode( $payload, true );
		}

		if ( ! is_array( $payload ) || empty( $payload['article']['titulo'] ) ) {
			wp_send_json_error( array( 'message' => __( 'No hay ningun articulo que guardar.', 'mil' ) ), 400 );
		}

		$article = $payload['article'];
		$idea    = isset( $payload['idea'] ) && is_array( $payload['idea'] ) ? $payload['idea'] : array();

		$article = array(
			'titulo'           => sanitize_text_field( isset( $article['titulo'] ) ? $article['titulo'] : '' ),
			'subtitulo'        => isset( $article['subtitulo'] ) ? sanitize_text_field( $article['subtitulo'] ) : '',
			'slug'             => isset( $article['slug'] ) ? sanitize_title( $article['slug'] ) : '',
			'sumilla'          => isset( $article['sumilla'] ) ? sanitize_textarea_field( $article['sumilla'] ) : '',
			'contenido_html'   => isset( $article['contenido_html'] ) ? wp_kses_post( $article['contenido_html'] ) : '',
			'keywords'         => isset( $article['keywords'] ) ? array_values( array_map( 'sanitize_text_field', (array) $article['keywords'] ) ) : array(),
			'meta_description' => isset( $article['meta_description'] ) ? sanitize_textarea_field( $article['meta_description'] ) : '',
			'faq'              => isset( $article['faq'] ) ? (array) $article['faq'] : array(),
			'notas_verificacion' => isset( $article['notas_verificacion'] ) ? sanitize_textarea_field( $article['notas_verificacion'] ) : '',
			'imagen_prompt'    => isset( $article['imagen_prompt'] ) ? sanitize_textarea_field( $article['imagen_prompt'] ) : '',
			'palabras'         => isset( $article['palabras'] ) ? (int) $article['palabras'] : 0,
		);

		if ( '' === $article['contenido_html'] ) {
			wp_send_json_error( array( 'message' => __( 'El contenido esta vacio.', 'mil' ) ), 400 );
		}

		$attachment_id = isset( $payload['image']['id'] ) ? (int) $payload['image']['id'] : 0;

		if ( $attachment_id ) {
			$attachment_id = (int) get_post( $attachment_id ) ? $attachment_id : 0;
		}

		$post_id = $ctx['gen']->save( $ctx['params'], $article, $idea, $attachment_id );

		if ( is_wp_error( $post_id ) ) {
			wp_send_json_error( array( 'message' => $post_id->get_error_message() ), 500 );
		}

		wp_send_json_success(
			array(
				'postId'  => $post_id,
				'status'  => ! empty( $ctx['params']['publicar'] ) ? 'publish' : 'draft',
				'editUrl' => get_edit_post_link( $post_id, '' ),
				'viewUrl' => get_permalink( $post_id ),
			)
		);
	}
}
