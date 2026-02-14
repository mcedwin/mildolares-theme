<div class="wrap">
	<h1 class="wp-heading-inline">Editar norma</h1>
	<div id="poststuff">
		<form action="<?php echo get_blog_base('normas','admin_guardar') ?>&id=<?php echo $id; ?>" method="post" id="guardar" class="myform">
			<input type="hidden" name="redirect" value="<?php echo get_admin_base('normas','admin_listado') ?>" />
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content">
					<label for="title">Título</label>
					<div id="titlediv">
						<div id="titlewrap">
							<input type="text" name="titulo" size="30" value="<?php echo $row->recu_titulo ?>" id="title" spellcheck="true" autocomplete="off">
						</div>
						<?php
						if(!empty($permalink)):
						?>
						<div class="inside">
							<strong>Enlace permanente:</strong> <a href="<?php echo $permalink; ?>"><?php echo $permalink; ?></a>
							<br><br>
						</div>
						<?php
						endif;
						?>
					</div>
					<label for="nombre">Nombre</label>
					<div class="formelem">
						<input type="text" name="nombre" id="nombre" value="<?php echo $row->recu_nombre?>">
					</div>
					<label for="sumilla">Sumilla</label>
					<div class="formelem">
						<textarea name="sumilla" id="sumilla"><?php echo $row->recu_sumilla ?></textarea>
					</div>
					<!-- /titlediv -->
					<div id="postdivrich" class="postarea wp-editor-expand">
						<?php
						wp_editor($row->recu_contenido,"contenido", array('textarea_rows'=>12, 'editor_class'=>'mytext_class'));
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
										<label class="post-attributes-label" for="categoria">Categoría</label>
									</p>
									<?php echo $selec; ?>

									<p class="post-attributes-label-wrapper">
										<label class="post-attributes-label" for="fecha">Fecha</label>
									</p>
									<input type="date" value="<?php echo ($row->recu_fecha); ?>" disabled="">

								</div>
								<div class="clear"></div>
								<div id="major-publishing-actions">
									<div id="publishing-action">
										<span class="spinner"></span>
										<input name="original_publish" type="hidden" id="original_publish" value="Publicar">
										<input type="submit" name="publish" id="publish" class="button button-primary button-large" value="Guardar">
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