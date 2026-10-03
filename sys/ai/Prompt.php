<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MIL_AI_Prompt {

	public static function site_context( $limit = 15 ) {
		$limit = max( 1, min( 50, (int) $limit ) );

		$lines   = array();
		$lines[] = 'Sitio: ' . wp_strip_all_tags( get_bloginfo( 'name' ) );
		$lines[] = 'URL: ' . home_url( '/' );
		$lines[] = 'Descripcion: ' . wp_strip_all_tags( get_bloginfo( 'description' ) );

		$locale = get_bloginfo( 'language' );
		if ( $locale ) {
			$lines[] = 'Idioma del sitio: ' . $locale;
		}

		$cats = get_categories(
			array(
				'number'     => 20,
				'hide_empty' => false,
				'orderby'    => 'count',
				'order'      => 'DESC',
			)
		);

		if ( $cats ) {
			$names = array();
			$coverage = array();

			foreach ( $cats as $cat ) {
				$names[]   = $cat->name . ' (' . $cat->count . ')';
				$coverage[] = $cat->name . ': ' . $cat->count . ' publicaciones';
			}

			$lines[] = 'Categorias existentes (nombre y numero de entradas): ' . implode( ', ', $names );
			$lines[] = 'Cobertura por categoria (huecos sugeridos: priorizar las con 0–3 publicaciones): ' . implode( ', ', $coverage );
		}

		$recent = get_posts(
			array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'numberposts'         => $limit,
				'orderby'             => 'date',
				'order'               => 'DESC',
				'suppress_filters'    => true,
				'ignore_sticky_posts' => true,
			)
		);

		if ( $recent ) {
			$titles = array();

			foreach ( $recent as $post ) {
				$cat_names = wp_list_pluck( get_the_category( $post->ID ), 'name' );
				$titles[]  = '- ' . wp_strip_all_tags( get_the_title( $post ) ) . ' [' . implode( ', ', $cat_names ) . ']';
			}

			$lines[] = 'Titulos ya publicados (NO repetir ni parafrasear de forma calcada):';
			$lines[] = implode( "\n", $titles );
		}

		return implode( "\n", $lines );
	}

	public static function params_block( array $p ) {
		$labels = array(
			'tema'        => 'Tema central',
			'audiencia'   => 'Audiencia',
			'objetivo'    => 'Objetivo del articulo',
			'tono'        => 'Tono',
			'longitud'    => 'Extension objetivo',
			'idioma'      => 'Idioma',
			'keywords'    => 'Palabras clave a posicionar',
			'categorias'  => 'Categorias de WordPress',
			'extra'       => 'Informacion adicional que debe incorporarse (datos, cifras, casos, enlaces, enfoque pedido)',
			'referencia'  => 'Material de referencia',
			'restricciones' => 'Restricciones / evitar',
			'estructura'  => 'Estructura preferida',
			'cta'         => 'Llamado a la accion final',
		);

		$out = array();

		foreach ( $labels as $key => $label ) {
			if ( empty( $p[ $key ] ) ) {
				continue;
			}

			if ( is_array( $p[ $key ] ) ) {
				$value = implode( ', ', $p[ $key ] );
			} else {
				$value = $p[ $key ];
			}

			$out[] = '- ' . $label . ': ' . $value;
		}

		return implode( "\n", $out );
	}

	public static function ideas_system() {
		return implode(
			"\n",
			array(
				'Eres un editor jefe de un portal web en español. Tu especialidad es proponer articulos originales que generen trafico organico y autoridad tematica.',
				'Responde SIEMPRE con un objeto JSON valido, sin texto adicional, sin bloques de codigo y sin comentarios.',
				'Esquema exacto:',
				'{"propuestas":[{"titulo":"","subtitulo":"","angulo":"","hook":"","keywords":[""],"categoria":"","nivel":"facil|medio|avanzado","razon":"","esqueleto":["h2","h2"]}]}',
				'Reglas:',
				'- "titulo": maximo 65 caracteres, claro, con la palabra clave principal de forma natural, sin mayusculas sostenidas ni signos de exclamacion.',
				'- "angulo": una frase que explique desde que perspectiva trata el tema y por que resulta util.',
				'- "hook": la promesa concreta que el lector obtiene (ej: "la lista de 7 pasos para...").',
				'- "categoria": debe coincidir con una de las categorias existentes del sitio, elige la mas cercana.',
				'- "esqueleto": entre 4 y 7titulos H2 en orden logico.',
				'- No repitas ninguno de los titulos ya publicados que se te proporcionen.',
				'- Varia los formatos entre propuestas: guias paso a paso, comparativas, errores comunes, casos reales, listas, analisis, opinion argumentada.',
			)
		);
	}

	public static function ideas_user( array $p ) {
		$parts = array();

		if ( ! empty( $p['usar_contexto'] ) ) {
			$parts[] = "CONTEXTO DEL SITIO\n" . self::site_context();
		}

		$parts[] = "PARAMETROS\n" . self::params_block( $p );

		$parts[] = sprintf(
			"TAREA\nPropón exactamente %d articulos sobre \"%s\". Cada propuesta debe ser distinta de las demas en formato y enfoque. Realiza un analisis de huecos tematicos (content gap) basandote en los titulos ya publicados y la cobertura por categoria: prioriza categorias con 0–3 publicaciones, evita duplicados exactos o muy cercanos, cubre preguntas no respondidas y sugiere ideas complementarias. No pidas confirmacion, devuelve propuestas listas para redactar.",
			max( 1, min( 12, (int) $p['cantidad'] ) ),
			$p['tema']
		);

		return implode( "\n\n", $parts );
	}

	public static function article_system() {
		return implode(
			"\n",
			array(
				'Eres un redactor SEO senior en español. Escribes articulos originales, utiles y verificables.',
				'Responde SIEMPRE con un objeto JSON valido, sin texto adicional, sin bloques de codigo.',
				'Esquema exacto:',
				'{"titulo":"","subtitulo":"","slug":"","sumilla":"","contenido_html":"","keywords":[""],"meta_description":"","faq":[{"pregunta":"","respuesta":""}],"imagen_prompt":"","notas_verificacion":""}',
				'Reglas de contenido:',
				'- "contenido_html": HTML semantico y limpio. Usa H2 y H3, listas <ul>/<ol>, <strong> solo para enfasis real y <table> cuando aporte comparacion. Nada de estilos inline, nada de iframes, nada de <script>, nada de <style>.',
				'- Empieza con una introduccion de 2 o 3 parrafos que enganche, sin repetir el titulo.',
				'- Reparte la palabra clave principal de forma natural en el H1, el primer parrafo, un H2 y la meta description.',
				'- Cierra con un H2 final de conclusiones y el llamado a la accion indicado.',
				'- No inventes datos estadisticos, cifras, nombres de personas, estudios ni enlaces concretos. Si mentionas un dato no verificable, redactalo de forma generica o dejalo fuera.',
				'- No copies frases de otras fuentes. Redacta desde cero.',
				'- "sumilla": entre 120 y 160 caracteres, sirve de meta description por defecto.',
				'- "meta_description": entre 140 y 158 caracteres.',
				'- "slug": en minusculas, sin acentos, separado por guiones, maximo 6 palabras.',
				'- "imagen_prompt": prompt en ingles para un generador de imagenes, en una sola frase, describiendo la escena concreta sin texto incrustado.',
				'- "faq": entre 3 y 5 preguntas y respuestas breves.',
				'- "notas_verificacion": lista separada por punto y coma con los datos que un editor debe verificar antes de publicar.',
			)
		);
	}

	public static function article_user( array $p, array $idea ) {
		$parts = array();

		if ( ! empty( $p['usar_contexto'] ) ) {
			$parts[] = "CONTEXTO DEL SITIO\n" . self::site_context();
		}

		$parts[] = "PARAMETROS\n" . self::params_block( $p );

		$idea_lines = array(
			'- Titulo propuesto: ' . ( isset( $idea['titulo'] ) ? $idea['titulo'] : '' ),
			'- Subtitulo: ' . ( isset( $idea['subtitulo'] ) ? $idea['subtitulo'] : '' ),
			'- Angulo: ' . ( isset( $idea['angulo'] ) ? $idea['angulo'] : '' ),
			'- Promesa al lector: ' . ( isset( $idea['hook'] ) ? $idea['hook'] : '' ),
			'- Esqueleto sugerido: ' . ( isset( $idea['esqueleto'] ) ? implode( ', ', (array) $idea['esqueleto'] ) : '' ),
		);

		$parts[] = "PROPUESTA A DESARROLLAR\n" . implode( "\n", $idea_lines );

		$parts[] = sprintf(
			"TAREA\nRedacta el articulo completo en HTML. Extension objetivo: %d palabras.",
			self::words_from_length( isset( $p['longitud'] ) ? $p['longitud'] : '' )
		);

		return implode( "\n\n", $parts );
	}

	public static function words_from_length( $length ) {
		$map = array(
			'corto'     => 800,
			'medio'     => 1500,
			'largo'     => 2500,
			'completo'  => 3500,
		);

		if ( isset( $map[ $length ] ) ) {
			return $map[ $length ];
		}

		return 1500;
	}

	public static function image_user( array $p, array $article ) {
		$style = ! empty( $p['estilo_imagen'] ) ? $p['estilo_imagen'] : 'editorial';
		$extra = ! empty( $p['extra'] ) ? $p['extra'] : '';

		$lines = array(
			'Prompt base: ' . ( ! empty( $article['imagen_prompt'] ) ? $article['imagen_prompt'] : $article['titulo'] ),
			'Titulo del articulo: ' . $article['titulo'],
			'Estilo: ' . $style . ' (composicion limpia, margenes amplios, sin texto, sin logotipos, sin marcas de agua, sin rostros reconocibles)',
			'Formato horizontal 3:2, alta resolucion, iluminacion natural.',
		);

		if ( '' !== $extra ) {
			$lines[] = 'Contexto adicional: ' . $extra;
		}

		return implode( "\n", $lines );
	}
}
