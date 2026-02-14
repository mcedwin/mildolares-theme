<div class="lis-search">
	<div class="container">
		<div class="row">
			<div class="col-md-7">
				<h3>Leyes <span><?php echo $modulos['legislacion']['total'] ?> resultados</span></h3>
			</div>
			<div class="col-md-5 text-right">
				<br>
				<a href="<?php echo get_bloginfo('url').'/legislacion/buscar_en/?q='.$q; ?>" class="more">Ver más</a>
			</div>
		</div>
		<div class="row">
			<?php
					foreach($modulos['legislacion']['registros'] as $row):
				?>
				<div class="col-md-2 item">
					<h4><a href="<?php echo $this->getPermalink($row->cont_slug,'legislacion'); ?>"><?php echo $row->cont_nombre ?></a></h4>
					<p>
						<?php echo substr($row->cont_sumilla,0,100)."..." ?>
					</p>
					<p class="blue">Vigencia:
						<?php echo $row->cont_fecha?>
					</p>
					<a href="#" class="pull-right save"><span class="glyphicon glyphicon-bookmark"></span></a>
					<div class="stars">
						<span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span>
					</div>
				</div>
				<?php endforeach;	?>
		</div>
		<div class="row">
			<div class="col-md-7">
				<h3>El Peruano <span><?php echo $elpe_total ?> resultados</span></h3>
			</div>
			<div class="col-md-5 text-right">
				<br>
				<a href="<?php echo get_bloginfo('url').'/elperuano/' ?>" class="more">Ver más</a>
			</div>
		</div>
		<div class="row">
			<?php
					foreach($elpe as $row):
				?>
				<div class="col-md-4 item">
					<h4><a href="<?php echo get_bloginfo('url').'/elperuano/'.$row->deta_elpe_id.'-'.$row->deta_id.'-'.urlstring($row->deta_subtitulo); ?>"><?php echo $row->deta_subtitulo ?></a></h4>
					<h5><?php echo $row->deta_titulo ?></a></h5>
					<div class="row">
						<div class="col-sm-6" style="padding-right:0">
							<a href="<?php echo get_bloginfo('url').'/elperuano/'.$row->deta_elpe_id.'-'.$row->deta_id.'-'.urlstring($row->deta_subtitulo); ?>"><img src="<?php echo get_bloginfo('url').'/web/wp-content/uploads/elperuano/'.$row->deta_img; ?>" class="img-fluid"></a>
						</div>
						<div class="col-sm-6">
							<p class="sumilla">
								<?php echo $row->deta_sumilla ?>
							</p>
							<p class="blue">Vigencia:
								<?php echo $row->elpe_fecha?>
							</p>

						</div>
					</div>
				</div>
				<?php endforeach;	?>
		</div>
		<div class="row">
			<div class="col-md-7">
				<h3>Jurisprudencia <span><?php echo $modulos['jurisprudencia']['total'] ?> resultados</span></h3>
			</div>
			<div class="col-md-5 text-right">
				<br>
				<a href="<?php echo get_bloginfo('url').'/jurisprudencia/buscar_en/?q='.$q; ?>" class="more">Ver más</a>
			</div>
		</div>
		<div class="row">
			<?php
					foreach($modulos['jurisprudencia']['registros'] as $row):
				?>
				<div class="col-md-4 item">
					<h4><a href="<?php echo $this->getPermalink($row->cont_slug,'jurisprudencia'); ?>"><?php echo $this->getPadre($row->cate_nivel) ?></a></h4>
					<h5><?php echo $row->cont_titulo ?></a></h5>
					<div class="row">
						<div class="col-sm-6" style="padding-right:0">
							<a href="<?php echo $this->getPermalink($row->cont_slug,'jurisprudencia'); ?>"><img src="<?php $src = wp_get_attachment_image_src( $row->cont_idarchivo, 'full');  echo $src[0]; ?>" class="img-fluid"></a>
						</div>
						<div class="col-sm-6">
							<p class="sumilla">
								<?php echo substr($row->cont_sumilla,0,200)."..." ?>
							</p>
							<p class="blue">Vigencia:
								<?php echo $row->cont_fecha?>
							</p>
							<a href="#" class="pull-right save"><span class="glyphicon glyphicon-bookmark"></span></a>
							<div class="stars">
								<span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span>
							</div>
						</div>
					</div>
				</div>
				<?php endforeach;	?>
		</div>
		
		<div class="row">
			<div class="col-md-7">
				<h3>Doctrina <span><?php echo $modulos['doctrina']['total'] ?> resultados</span></h3>
			</div>
			<div class="col-md-5 text-right">
				<br>
				<a href="<?php echo get_bloginfo('url').'/doctrina/buscar_en/?q='.$q; ?>" class="more">Ver más</a>
			</div>
		</div>
		<div class="row">
			<?php
					foreach($modulos['doctrina']['registros'] as $row):
				?>
				<div class="col-md-2 item">
				<a href="<?php echo $this->getPermalink($row->cont_slug,'doctrina'); ?>"><img src="<?php $src = wp_get_attachment_image_src( $row->cont_idarchivo, 'full');  echo $src[0]; ?>" class="img-fluid"></a>
				<h4><a href="<?php echo $this->getPermalink($row->cont_slug,'doctrina'); ?>"><?php echo $row->cont_titulo ?></a></h4>
				<h5><?php echo $row->cont_subtitulo ?></a></h5>
				<a href="#" class="pull-right save"><span class="glyphicon glyphicon-bookmark"></span></a>
				<div class="stars">
					<span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span>
				</div>
			</div>
				<?php endforeach;	?>
		</div>
		<div class="row">
			<div class="col-md-7">
				<h3>Modelos escritos <span><?php echo $modulos['escritos']['total'] ?> resultados</span></h3>
			</div>
			<div class="col-md-5 text-right">
				<br>
				<a href="<?php echo get_bloginfo('url').'/escritos/buscar_en/?q='.$q; ?>" class="more">Ver más</a>
			</div>
		</div>
		<div class="row">
			<?php
					foreach($modulos['escritos']['registros'] as $row):
				?>
				<div class="col-md-2 item">
				<a href="<?php echo $this->getPermalink($row->cont_slug,'escritos'); ?>"><img src="<?php echo $row->cont_imagen; ?>" class="img-fluid"></a>
				<h4><a href="<?php echo $this->getPermalink($row->cont_slug,'escritos'); ?>"><?php echo $row->cont_titulo ?></a></h4>
				<h5><?php echo $row->cont_subtitulo ?></a></h5>
				<a href="#" class="pull-right save"><span class="glyphicon glyphicon-bookmark"></span></a>
				<div class="stars">
					<span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span>
				</div>
			</div>
				<?php endforeach;	?>
		</div>
	</div>
</div>