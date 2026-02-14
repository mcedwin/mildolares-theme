<div class="wrap">
	<h1 class="wp-heading-inline"><?php echo $titulo ?></h1>
	<div id="poststuff">
		<form action="<?php echo get_blog_base('academia','admin_guardar') ?>&id=<?php echo $id; ?>" method="post" id="guardar" class="myform">
			<input type="hidden" name="redirect" value="<?php echo get_admin_base('academia','admin_listado') ?>" />
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content">
					<label for="title">Título</label>
					<div id="titlediv">
						<div id="titlewrap">
							<input type="text" name="titulo" size="30" value="<?php echo $row->acad_titulo ?>" id="title" spellcheck="true" autocomplete="off">
						</div>
						<?php
						if(!empty($permalink)):
						?>
						<div class="inside">
							<strong>Enlace permanente:</strong> <a href="http://localhost/web/2018/03/03/hola-mundo/">http://localhost/web/2018/03/03/hola-mundo/</a>
						</div>
						<?php
						endif;
						?>
					</div>
					<label for="ubicacion">Ubicación</label>
					<div class="formelem">
						<input type="text" name="ubicacion" id="ubicacion" value="<?php echo $row->acad_ubicacion ?>">
					</div>
					
					<!-- /titlediv -->
					<div id="postdivrich" class="postarea wp-editor-expand">
						<?php
						wp_editor($row->acad_contenido,"contenido", array('textarea_rows'=>12, 'editor_class'=>'mytext_class'));
						?>
					</div>
				</div>
				<!-- /post-body-content -->
				<div id="postbox-container-1" class="postbox-container">
					<div id="submitdiv" class="postbox ">
						<h2 style="border-bottom:1px solid #eee"><span>Publicar</span></h2>
						<div class="inside">
							<div class="submitbox">
								<div class="misc-pub-section">
									<p class="post-attributes-label-wrapper">
										<label class="post-attributes-label" for="region">Región</label>
									</p>
									<?php echo $select_region; ?>
									<p class="post-attributes-label-wrapper">
										<label class="post-attributes-label" for="empresa">Empresa</label>
									</p>
									<?php echo $select_empresa; ?>
									<p class="post-attributes-label-wrapper">
										<label class="post-attributes-label" for="desde">Desde</label>
									</p>
									<input type="date" name="desde" id="desde" value="<?php echo $row->acad_desde; ?>" >
									<p class="post-attributes-label-wrapper">
										<label class="post-attributes-label" for="hasta">Hasta</label>
									</p>
									<input type="date" name="hasta" id="hasta" value="<?php echo $row->acad_hasta; ?>" >
									<p class="post-attributes-label-wrapper">
										<label class="post-attributes-label" for="presupuesto">Presupesto</label>
									</p>
									<input type="text" name="presupuesto" id="presupuesto" value="<?php echo $row->acad_presupuesto; ?>" >
									<p class="post-attributes-label-wrapper">
										<label class="post-attributes-label" for="nivel">Nivel</label>
									</p>
									<input type="text" name="nivel" id="nivel" value="<?php echo $row->acad_nivel; ?>" >
									<p class="post-attributes-label-wrapper">
										<label class="post-attributes-label" for="modalidad">Modalidad</label>
									</p>
									<input type="text" name="modalidad" id="modalidad" value="<?php echo $row->acad_modalidad; ?>" >
									<p class="post-attributes-label-wrapper">
										<label class="post-attributes-label" for="vacantes">Vacantes</label>
									</p>
									<input type="text" name="vacantes" id="vacantes" value="<?php echo $row->acad_vacantes; ?>" >
									<div>
									    <label for="archivo">Archivo PDF</label>
									    <input type="text" name="archivo" id="archivo" value="<?php echo $row->acad_archivo ?>" class="regular-text">
									    <input type="hidden" name="idarchivo" id="idarchivo" value="<?php echo $row->acad_idarchivo ?>">
									    <input type="button" name="upload-btn" data-count="1" id="upload-btn" class="button-secondary" value="Subir Archivo">
									    <input type="button" name="clear-btn" data-count="1" id="clear-btn" class="button-secondary" value="Quitar Archivo">
									</div>

								</div>
								<div class="clear"></div>
								<div id="major-publishing-actions">
									<div id="publishing-action">
										<span class="spinner"></span>
										<input name="original_publish" type="hidden" id="original_publish" value="Publicar">
										<input type="submit" name="publish" id="publish" class="button button-primary button-large" value="Publicar">
									</div>
									<div class="clear"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>