<div class="wrap">
	<h1 class="wp-heading-inline"><?php echo $titulo ?></h1>
	<div id="poststuff">
		<form action="<?php echo get_blog_base('elperuano', 'admin_guardar') ?>&id=<?php echo $id; ?>&sid=<?php echo $sid; ?>" method="post" id="guardar" class="myform">
			<input type="hidden" id="query" value="<?php echo get_blog_base('elperuano', 'admin_buscar') ?>" />
			<input type="hidden" name="redirect" value="" />
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content">
					<label for="title">Título</label>
					<div id="titlediv">
						<div id="titlewrap">
							<input type="text" class="tslug" name="titulo" size="30" value="<?php echo $row->deta_titulo ?>" id="title" spellcheck="true" autocomplete="off">
						</div>
						<?php
						if (!empty($baselink)) :
						?>
							<div class="inside">
								<div id="edit-slug-box" class="hide-if-no-js">
									<strong>Enlace permanente:</strong>
									<span id="sample-permalink"><a href="<?php echo get_bloginfo('url').'/elperuano/'.$row->deta_elpe_id.'-'.$row->deta_id.'/'.urlstring($row->deta_subtitulo); ?>"><?php echo get_bloginfo('url').'/elperuano/'.$row->deta_elpe_id.'-'.$row->deta_id.'/'.urlstring($row->deta_subtitulo); ?></a></span>
								</div>
							</div>
						<?php
						endif;
						?>
					</div>
					<div><label for="subtitulo">Subtitulo</label>
						<div class="formelem">
							<input type="text" name="subtitulo" id="subtitulo" value="<?php echo $row->deta_subtitulo ?>">
						</div>
					</div>
					<div>
						<label for="sumilla">Sumilla</label>
						<div class="formelem">
							<textarea name="sumilla" rows="4" id="sumilla"><?php echo $row->deta_sumilla ?></textarea>
						</div>
					</div>
					<!-- /titlediv -->
					<div id="postdivrich" class="postarea wp-editor-expand" <?php if (false) echo 'style="display:none"' ?>>
						<?php
						wp_editor($row->deta_contenido, "contenido", array('textarea_rows' => 12, 'editor_class' => 'mytext_class'));
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

									<div>
										<div>
											<a href="<?php echo $base.$row->deta_pdf; ?>" target="blank">Archivo PDF</a>
										</div>
										<br>
										<div>
											<img src="<?php echo $base.$row->deta_img; ?>">
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