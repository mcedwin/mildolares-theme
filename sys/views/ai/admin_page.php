<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$mil_ai_tonos = array(
	'profesional'   => __( 'Profesional y cercano', 'mil' ),
	'cercano'       => __( 'Cercano y conversacional', 'mil' ),
	'didactico'     => __( 'Didactico, paso a paso', 'mil' ),
	'divulgativo'   => __( 'Divulgativo, claro y sencillo', 'mil' ),
	'argumentativo' => __( 'Argumentativo, con criterio', 'mil' ),
);

$mil_ai_longitudes = array(
	'corto'    => __( 'Corta - 800 palabras', 'mil' ),
	'medio'    => __( 'Media - 1500 palabras', 'mil' ),
	'largo'    => __( 'Larga - 2500 palabras', 'mil' ),
	'completo' => __( 'Completa - 3500 palabras', 'mil' ),
);

$mil_ai_estructuras = array(
	'libre'            => __( 'Libre, la que convenga', 'mil' ),
	'paso a paso'      => __( 'Guia paso a paso', 'mil' ),
	'comparativa'      => __( 'Comparativa de opciones', 'mil' ),
	'preguntas frecuentes' => __( 'Preguntas frecuentes', 'mil' ),
	'casos reales'     => __( 'Casos reales', 'mil' ),
);

$mil_ai_estilos = array(
	'editorial'       => __( 'Editorial sobrio', 'mil' ),
	'ilustracion plana' => __( 'Ilustracion plana', 'mil' ),
	'fotografia'      => __( 'Fotografia', 'mil' ),
	'minimalista'     => __( 'Minimalista', 'mil' ),
	'infografia'      => __( 'Infografia', 'mil' ),
);

$mil_ai_tamanos = array(
	'1536x1024' => __( 'Horizontal 3:2 (recomendado)', 'mil' ),
	'1024x1024' => __( 'Cuadrada', 'mil' ),
	'1024x1536' => __( 'Vertical', 'mil' ),
);
?>
<div class="wrap mil-ai">

	<h1 class="mil-ai__title"><?php esc_html_e( 'MIL IA - Redaccion asistida', 'mil' ); ?></h1>
	<p class="mil-ai__lead">
		<?php esc_html_e( 'Describe que quieres publicar. La IA propone titulares, escribe el articulo en HTML y genera la imagen de portada. Todo queda como borrador hasta que lo apruebes.', 'mil' ); ?>
	</p>

	<div class="mil-ai__status mil-ai__status--<?php echo $info['env_exists'] ? 'ok' : 'warn'; ?>">
		<span class="mil-ai__dot"></span>
		<?php if ( ! $info['env_exists'] ) : ?>
			<strong><?php esc_html_e( 'Falta el archivo .env del tema.', 'mil' ); ?></strong>
			<?php esc_html_e( 'Copia .env.example a .env y coloca tu MIL_AI_API_KEY.', 'mil' ); ?>
			<code><?php echo esc_html( str_replace( get_template_directory(), '…/themes/mil', $info['env_file'] ) ); ?></code>
		<?php elseif ( '' === $info['key_masked'] ) : ?>
			<strong><?php esc_html_e( 'MIL_AI_API_KEY vacio en el .env.', 'mil' ); ?></strong>
		<?php else : ?>
			<strong><?php esc_html_e( 'OpenAI conectado.', 'mil' ); ?></strong>
			<span class="mil-ai__sep"></span>
			<?php esc_html_e( 'Texto:', 'mil' ); ?> <code><?php echo esc_html( $info['text_model'] ); ?></code>
			<span class="mil-ai__sep"></span>
			<?php esc_html_e( 'Imagen:', 'mil' ); ?> <code><?php echo esc_html( $info['image_model'] ); ?></code>
			<span class="mil-ai__sep"></span>
			<code><?php echo esc_html( $info['base'] ); ?></code>
			<?php if ( '' !== $info['org'] ) : ?>
				<span class="mil-ai__sep"></span>
				<?php esc_html_e( 'Org:', 'mil' ); ?> <code><?php echo esc_html( $info['org'] ); ?></code>
			<?php endif; ?>
		<?php endif; ?>
	</div>

	<?php if ( ! $info['env_exists'] || '' === $info['key_masked'] ) : ?>
		<div class="mil-ai__notice mil-ai__notice--error">
			<?php esc_html_e( 'Sin credenciales la pantalla es solo de consulta. Configura el .env para empezar a generar.', 'mil' ); ?>
		</div>
	<?php endif; ?>

	<section class="mil-ai__panel">
		<h2 class="mil-ai__panel-title"><?php esc_html_e( 'Imágenes gratuitas para posts sin foto', 'mil' ); ?></h2>

		<p class="mil-ai__hint">
			<?php
			printf(
				/* translators: %s: number of posts without featured image */
				esc_html__( 'Hay %s publicaciones sin imagen destacada. La herramienta busca una foto libre de derechos relacionada con el título y la categoría, y la asigna como portada.', 'mil' ),
				'<strong>' . esc_html( (string) $missing ) . '</strong>'
			);
			?>
			<span class="mil-ai__sep"></span>
			<?php
			if ( 'pexels' === $provider ) {
				esc_html_e( 'Fuente: Pexels (clave en .env).', 'mil' );
			} else {
				esc_html_e( 'Fuente: Openverse (sin clave, licencias CC0/dominio público). Limitado a unas 100 consultas al día; para uso intenso añade MIL_PEXELS_API_KEY al .env.', 'mil' );
			}
			?>
		</p>

		<div class="mil-ai__actions">
			<label class="mil-ai__field mil-ai__field--inline">
				<span class="mil-ai__label"><?php esc_html_e( 'Posts a procesar', 'mil' ); ?></span>
				<input type="number" id="mil-ai-images-limit" value="10" min="1" max="30">
			</label>
			<button type="button" class="button button-primary mil-ai__btn" id="mil-ai-autofill">
				<?php esc_html_e( 'Asignar imágenes gratuitas', 'mil' ); ?>
			</button>
			<span class="mil-ai__spinner" id="mil-ai-spinner-images" hidden></span>
		</div>

		<div id="mil-ai-images-results" class="mil-ai__images"></div>
	</section>

	<div class="mil-ai__layout">

		<section class="mil-ai__panel mil-ai__panel--params">
			<h2 class="mil-ai__panel-title">1. <?php esc_html_e( 'Parametros', 'mil' ); ?></h2>

			<form id="mil-ai-form" class="mil-ai__form">
				<?php wp_nonce_field( MIL_AI_Admin::NONCE, 'mil_ai_form_nonce' ); ?>

				<label class="mil-ai__field">
					<span class="mil-ai__label"><?php esc_html_e( 'Nicho del sitio', 'mil' ); ?></span>
					<input type="text" name="nicho" value="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" placeholder="<?php esc_attr_e( 'finanzas personales en Peru', 'mil' ); ?>">
				</label>

				<label class="mil-ai__field">
					<span class="mil-ai__label"><?php esc_html_e( 'Tema central *', 'mil' ); ?></span>
					<textarea name="tema" rows="3" required placeholder="<?php esc_attr_e( 'Como invertir los primeros 500 dolares de un sueldo en Peru', 'mil' ); ?>"></textarea>
				</label>

				<label class="mil-ai__field">
					<span class="mil-ai__label"><?php esc_html_e( 'Audiencia', 'mil' ); ?></span>
					<input type="text" name="audiencia" placeholder="<?php esc_attr_e( 'Jvenes de 25 a 35 años que ganan entre 1500 y 3000 soles', 'mil' ); ?>">
				</label>

				<label class="mil-ai__field">
					<span class="mil-ai__label"><?php esc_html_e( 'Objetivo', 'mil' ); ?></span>
					<input type="text" name="objetivo" value="<?php esc_attr_e( 'tráfico orgánico y autoridad', 'mil' ); ?>" placeholder="<?php esc_attr_e( 'posicionar en Google, generar clics, captar correos', 'mil' ); ?>">
				</label>

				<div class="mil-ai__row">
					<label class="mil-ai__field">
						<span class="mil-ai__label"><?php esc_html_e( 'Tono', 'mil' ); ?></span>
						<select name="tono">
							<?php foreach ( $mil_ai_tonos as $mil_k => $mil_v ) : ?>
								<option value="<?php echo esc_attr( $mil_k ); ?>"><?php echo esc_html( $mil_v ); ?></option>
							<?php endforeach; ?>
						</select>
					</label>

					<label class="mil-ai__field">
						<span class="mil-ai__label"><?php esc_html_e( 'Extension', 'mil' ); ?></span>
						<select name="longitud">
							<?php foreach ( $mil_ai_longitudes as $mil_k => $mil_v ) : ?>
								<option value="<?php echo esc_attr( $mil_k ); ?>" <?php selected( $mil_k, 'medio' ); ?>><?php echo esc_html( $mil_v ); ?></option>
							<?php endforeach; ?>
						</select>
					</label>
				</div>

				<div class="mil-ai__row">
					<label class="mil-ai__field">
						<span class="mil-ai__label"><?php esc_html_e( 'Estructura', 'mil' ); ?></span>
						<select name="estructura">
							<?php foreach ( $mil_ai_estructuras as $mil_k => $mil_v ) : ?>
								<option value="<?php echo esc_attr( $mil_k ); ?>"><?php echo esc_html( $mil_v ); ?></option>
							<?php endforeach; ?>
						</select>
					</label>

					<label class="mil-ai__field">
						<span class="mil-ai__label"><?php esc_html_e( 'Propuestas a generar', 'mil' ); ?></span>
						<input type="number" name="cantidad" value="5" min="1" max="12">
					</label>
				</div>

				<label class="mil-ai__field">
					<span class="mil-ai__label"><?php esc_html_e( 'Palabras clave a posicionar', 'mil' ); ?></span>
					<input type="text" name="keywords" placeholder="<?php esc_attr_e( 'invertir 500 dolares, primer sueldo, ahorro', 'mil' ); ?>">
					<span class="mil-ai__hint"><?php esc_html_e( 'Separadas por coma.', 'mil' ); ?></span>
				</label>

				<label class="mil-ai__field">
					<span class="mil-ai__label"><?php esc_html_e( 'Informacion adicional que debe usar', 'mil' ); ?></span>
					<textarea name="extra" rows="5" placeholder="<?php esc_attr_e( 'Mencionar que el salario minimo es 1600 soles, que el fondo de emergencia recomendado es de 3 a 6 meses de gastos, y enlazar a la guia de tarjeta de credito.', 'mil' ); ?>"></textarea>
					<span class="mil-ai__hint"><?php esc_html_e( 'Datos, cifras, enfoque, enlaces o casos que quieres que incorpore.', 'mil' ); ?></span>
				</label>

				<label class="mil-ai__field">
					<span class="mil-ai__label"><?php esc_html_e( 'Material de referencia', 'mil' ); ?></span>
					<textarea name="referencia" rows="3" placeholder="<?php esc_attr_e( 'URLs, fragmentos de texto, resultados de busqueda que debe tener en cuenta.', 'mil' ); ?>"></textarea>
				</label>

				<label class="mil-ai__field">
					<span class="mil-ai__label"><?php esc_html_e( 'Evitar', 'mil' ); ?></span>
					<input type="text" name="restricciones" placeholder="<?php esc_attr_e( 'no prometer resultados, no citar normativa sin fuente, no usar primera persona', 'mil' ); ?>">
				</label>

				<label class="mil-ai__field">
					<span class="mil-ai__label"><?php esc_html_e( 'Llamado a la accion final', 'mil' ); ?></span>
					<input type="text" name="cta" placeholder="<?php esc_attr_e( 'Suscribete al boletin y descarga la plantilla de presupuesto.', 'mil' ); ?>">
				</label>

				<label class="mil-ai__field">
					<span class="mil-ai__label"><?php esc_html_e( 'Categorias de WordPress', 'mil' ); ?></span>
					<select name="categorias[]" multiple size="5">
						<?php foreach ( $categories as $mil_cat ) : ?>
							<option value="<?php echo esc_attr( $mil_cat->name ); ?>">
								<?php echo esc_html( $mil_cat->name . ' (' . $mil_cat->count . ')' ); ?>
							</option>
						<?php endforeach; ?>
					</select>
					<span class="mil-ai__hint"><?php esc_html_e( 'Ctrl o Cmd para varias. Si eliges una, el modelo intentara respetar esas lineas editoriales.', 'mil' ); ?></span>
				</label>

				<div class="mil-ai__row">
					<label class="mil-ai__field">
						<span class="mil-ai__label"><?php esc_html_e( 'Estilo de imagen', 'mil' ); ?></span>
						<select name="estilo_imagen">
							<?php foreach ( $mil_ai_estilos as $mil_k => $mil_v ) : ?>
								<option value="<?php echo esc_attr( $mil_k ); ?>"><?php echo esc_html( $mil_v ); ?></option>
							<?php endforeach; ?>
						</select>
					</label>

					<label class="mil-ai__field">
						<span class="mil-ai__label"><?php esc_html_e( 'Formato de imagen', 'mil' ); ?></span>
						<select name="tamano_imagen">
							<?php foreach ( $mil_ai_tamanos as $mil_k => $mil_v ) : ?>
								<option value="<?php echo esc_attr( $mil_k ); ?>"><?php echo esc_html( $mil_v ); ?></option>
							<?php endforeach; ?>
						</select>
					</label>
				</div>

				<div class="mil-ai__checks">
					<label><input type="checkbox" name="usar_contexto" value="1" checked> <?php esc_html_e( 'Usar contexto del sitio (titulos ya publicados y categorias, para no repetir)', 'mil' ); ?></label>
					<label><input type="checkbox" name="con_imagen" value="1"> <?php esc_html_e( 'Generar imagen de portada con IA', 'mil' ); ?></label>
					<label><input type="checkbox" name="sobrescribir_tags" value="1"> <?php esc_html_e( 'Usar las palabras clave del articulo como etiquetas, ignora las de la propuesta', 'mil' ); ?></label>
					<label><input type="checkbox" name="publicar" value="1"> <?php esc_html_e( 'Publicar directamente (si lo dejo sin marcar, se guarda como borrador)', 'mil' ); ?></label>
				</div>

				<div class="mil-ai__actions">
				<button type="button" class="button button-primary mil-ai__btn" id="mil-ai-propose">
					<?php esc_html_e( 'Proponer articulos', 'mil' ); ?>
				</button>
				<span class="mil-ai__spinner" id="mil-ai-spinner-propose" hidden></span>
			</div>
		</form>
	</section>

	<section class="mil-ai__panel mil-ai__panel--ideas">
		<h2 class="mil-ai__panel-title">2. <?php esc_html_e( 'Propuestas', 'mil' ); ?></h2>
		<p class="mil-ai__hint"><?php esc_html_e( 'Selecciona los titulos a aprobar. Al aprobarlos se generaran y publicaran automaticamente.', 'mil' ); ?></p>

		<div id="mil-ai-ideas" class="mil-ai__ideas">
			<p class="mil-ai__empty"><?php esc_html_e( 'Aun no hay propuestas.', 'mil' ); ?></p>
		</div>

		<div class="mil-ai__actions">
			<button type="button" class="button button-primary mil-ai__btn" id="mil-ai-approve" disabled>
				<?php esc_html_e( 'Aprobar y publicar seleccionados', 'mil' ); ?>
			</button>
			<button type="button" class="button mil-ai__btn" id="mil-ai-select-all"><?php esc_html_e( 'Marcar / desmarcar todo', 'mil' ); ?></button>
			<span class="mil-ai__spinner" id="mil-ai-spinner-write" hidden></span>
		</div>
	</section>
	</div>

	<section class="mil-ai__panel mil-ai__panel--results" id="mil-ai-results" hidden>
		<h2 class="mil-ai__panel-title">3. <?php esc_html_e( 'Articulos generados', 'mil' ); ?></h2>
		<div id="mil-ai-articles" class="mil-ai__articles"></div>
	</section>

	<?php if ( $history ) : ?>
		<section class="mil-ai__panel">
			<h2 class="mil-ai__panel-title"><?php esc_html_e( 'Historial', 'mil' ); ?></h2>
			<table class="mil-ai__table widefat striped">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Titulo', 'mil' ); ?></th>
						<th><?php esc_html_e( 'Estado', 'mil' ); ?></th>
						<th><?php esc_html_e( 'Palabras', 'mil' ); ?></th>
						<th><?php esc_html_e( 'Fecha', 'mil' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $history as $mil_item ) : ?>
						<tr>
							<td>
								<a href="<?php echo esc_url( get_edit_post_link( (int) $mil_item['post_id'], '' ) ); ?>">
									<?php echo esc_html( $mil_item['titulo'] ); ?>
								</a>
							</td>
							<td><?php echo 'draft' === $mil_item['status'] ? esc_html__( 'Borrador', 'mil' ) : esc_html__( 'Publicado', 'mil' ); ?></td>
							<td><?php echo esc_html( (string) (int) $mil_item['palabras'] ); ?></td>
							<td><?php echo esc_html( mysql2date( 'd/m/Y H:i', $mil_item['fecha'] ) ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</section>
	<?php endif; ?>

</div>
