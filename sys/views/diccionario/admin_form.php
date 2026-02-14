<div class="wrap">
	<h1 class="wp-heading-inline"><?php echo $titulo ?></h1>
	<div id="poststuff">
		<form action="<?php echo get_blog_base('diccionario','admin_guardar') ?>&id=<?php echo $id; ?>" method="post" id="guardar" class="myform">
			<input type="hidden" name="redirect" value="<?php echo get_admin_base('diccionario','admin_listado') ?>" />
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content">
					<label for="title">Término</label>
					<div id="titlediv">
						<div id="titlewrap">
							<input type="text" name="nombre" size="30" value="<?php echo $row->dicc_nombre ?>" id="title" spellcheck="true" autocomplete="off">
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
					
					<label for="sumilla">Descripción</label>
					<div class="formelem">
						<textarea name="descripcion" id="descripcion"><?php echo $row->dicc_descripcion ?></textarea>
					</div>
					<!-- /titlediv -->
					<div id="postdivrich" class="postarea wp-editor-expand">
						<?php
						wp_editor($row->dicc_contenido,"contenido", array('textarea_rows'=>12, 'editor_class'=>'mytext_class'));
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