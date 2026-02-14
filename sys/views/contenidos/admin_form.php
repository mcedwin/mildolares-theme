<div class="wrap">
	<h1 class="wp-heading-inline"><?php echo $titulo ?></h1>
	<div id="poststuff">
		<form action="<?php echo get_blog_base($this->titulos[$this->tipo][1], 'admin_guardar') ?>&id=<?php echo $id; ?>" method="post" id="guardar" class="myform">
			<input type="hidden" id="query" value="<?php echo get_blog_base($this->titulos[$this->tipo][1], 'admin_buscar') ?>" />
			<input type="hidden" name="redirect" value="" />
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content">
					<label for="title">Título</label>
					<div id="titlediv">
						<div id="titlewrap">
							<input type="text" class="tslug" name="titulo" size="30" value="<?php echo $row->cont_titulo ?>" id="title" spellcheck="true" autocomplete="off">
						</div>
						<?php
						if (!empty($baselink)) :
						?>
							<div class="inside">
								<div id="edit-slug-box" class="hide-if-no-js">
									<strong>Enlace permanente:</strong>
									<span id="sample-permalink"><a href="<?php echo $baselink.$row->cont_slug; ?>"><?php echo $baselink; ?><span class="vslug"><?php echo $row->cont_slug; ?></span></a></span>
									<input type="hidden" style="display:none" name="slug" class="slug" value="<?php echo $row->cont_slug; ?>">
									‎<span id="edit-slug-buttons"><button type="button" class="edit-slug button button-small hide-if-no-js" aria-label="Editar el enlace permanente">Editar</button></span>
								</div>
								<script>
									jQuery('.tslug').change(function(){
										if(jQuery('.slug').val().length==0){
											a = jQuery('.tslug').val()
											a = a.replace(/ /gi,'-')
											jQuery('.slug').val(a);
											jQuery('.vslug').text(a);
										}
									});
									jQuery('.edit-slug').click(function(){
										//jQuery('.slug').show();
										var a  = prompt("Slug",jQuery('.slug').val());
										if(a!=null){
											jQuery('.vslug').text(a);
											jQuery('.slug').val(a);
										}
									})
								</script>
							</div>
						<?php
						endif;
						?>
					</div>
					<div <?php if ($this->tipo == 1) echo 'style="display:none"' ?>><label for="subtitulo"><?php echo $this->tipo == 3 || $this->tipo == 2 ? 'Autor' : 'Subtitulo'; ?></label>
						<div class="formelem">
							<input type="text" name="subtitulo" id="subtitulo" value="<?php echo $row->cont_subtitulo ?>">
						</div>
					</div>
					<div <?php if ($this->tipo == 3) echo 'style="display:none"' ?>>
						<label for="sumilla">Sumilla</label>
						<div class="formelem">
							<textarea name="sumilla" rows="4" id="sumilla"><?php echo $row->cont_sumilla ?></textarea>
						</div>
					</div>
					<!-- /titlediv -->
					<div id="postdivrich" class="postarea wp-editor-expand" <?php if (false) echo 'style="display:none"' ?>>
						<?php
						wp_editor($row->cont_contenido, "contenido", array('textarea_rows' => 12, 'editor_class' => 'mytext_class'));
						?>
					</div>
					<div <?php if ($this->tipo != 2) echo 'style="display:none"' ?>>
						<label for="sumilla">Fuente</label>
						<div class="formelem">
							<textarea name="fuente" rows="4" id="fuente"><?php echo $row->cont_fuente ?></textarea>
						</div>
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
									<input type="date" value="<?php echo ($row->cont_fecha); ?>" name="fecha">


									<div class="checkbox">
										<label>
											<input name="principal" type="checkbox" value="1" <?php echo ($row->cont_principal == '1' ? 'checked' : '') ?> /> Principal</label>
									</div>

									<div <?php if ($this->tipo == 3) echo 'style="display:none"'; ?>>
										<div>
											<label for="archivo"><?php echo $this->tipo == 3 ? 'Archivo WORD' : 'Archivo PDF'; ?></label>
											<input type="text" name="archivo" id="archivo" value="<?php echo $row->cont_archivo ?>" class="regular-text">
											<input type="hidden" name="idarchivo" id="idarchivo" value="<?php echo $row->cont_idarchivo ?>">
											<input type="button" name="upload-btn" data-count="1" id="upload-btn" class="button-secondary" value="Subir Archivo">
											<input type="button" name="clear-btn" data-count="1" id="clear-btn" class="button-secondary" value="Quitar Archivo">
										</div>
										<br>
										<div style="<?php echo ($this->tipo == 3 ? 'display:block' : 'display:none'); ?>">
											<label for="imagen">Imagen representativa</label>
											<input type="text" name="imagen" id="imagen" value="<?php echo $row->cont_imagen ?>" class="regular-text">
											<input type="hidden" name="idimagen" id="idimagen" value="<?php echo $row->cont_idimagen ?>">
											<input type="button" name="uploadi-btn" data-count="1" id="uploadi-btn" class="button-secondary" value="Subir Archivo">
											<input type="button" name="cleari-btn" data-count="1" id="cleari-btn" class="button-secondary" value="Quitar Archivo">
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