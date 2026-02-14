<div class="lis-search">
	<div class="container">
		<?php //die($this->tipo."###"); ?>
		<?php if($this->tipo==4): ?>
		<div class="row">
			<div class="col-md-7">
				<h3>Legislación <span><?php echo $total ?> resultados</span></h3>
			</div>
		</div>
		<div class="row">
			<?php
				$i=0;
					foreach($result as $row):
				?>
		
			
				<div class="col-md-12 item">
					<h4><a href="<?php echo get_bloginfo('url').'/legislacion/'.$row->cont_slug; ?>"><?php echo $row->cont_titulo ?></a></h4>
					<p><?php echo substr(strip_tags($row->cont_sumilla),0,200)."..." ?></p>
					<p class="blue">Vigencia:
						<?php echo $row->cont_fecha?>
					</p>
				</div>
				<?php 
				$i++;
				//if($i%6==0)echo '</div><div class="row">';
				endforeach;	

				?>
		</div>
		
		<?php elseif($this->tipo==1): ?>
		<div class="row">
			<div class="col-md-7">
				<h3>Jurisprudencia <span><?php echo $total ?> resultados</span></h3>
			</div>
		</div>
		<div class="row">
			<?php
			$i=0;
					foreach($result as $row):
				?>
				<!--
				<div class="col-md-4 item">
				    
					<h4><a href="<?php echo get_bloginfo('url').'/jurisprudencia/'.$row->cont_slug; ?>"><?php echo $this->getPadre($row->cate_nivel) ?></a></h4>
					<h5><?php echo $row->cont_titulo ?></a></h5>
					<div class="row">
						<div class="col-sm-6" style="padding-right:0">
							<a href="<?php echo get_bloginfo('url').'/jurisprudencia/'.$row->cont_slug; ?>"><img src="<?php $src = wp_get_attachment_image_src( $row->cont_idarchivo, 'full');  echo $src[0]; ?>" class="img-fluid"></a>
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
				-->
				<div class="col-md-12 item">
				    <h4><a href="<?php echo get_bloginfo('url').'/jurisprudencia/'.$row->cont_slug; ?>"><?php echo $row->cont_titulo ?></a></h4>
				    <p class="blue">Vigente:
						<?php echo $row->cont_fecha?>
					</p>
				    <p class="sumilla">
						<?php echo substr($row->cont_sumilla,0,200)."..." ?>
					</p>	
				</div>
				<?php 
					$i++;
					//if($i%3==0)echo '</div><div class="row">';
				endforeach;	
				?>
		</div>
		<?php elseif($this->tipo==2): ?>
		<div class="row">
			<div class="col-md-7">
				<h3>Doctrina <span><?php echo $total ?> resultados</span></h3>
			</div>
		</div>
		<div class="row">
			<?php
			$i=0;
					foreach($result as $row):
				?>
				<!--
				<div class="col-md-2 item">
    				<a href="<?php echo get_bloginfo('url').'/doctrina/'.$row->cont_slug; ?>"><img src="<?php $src = wp_get_attachment_image_src( $row->cont_idarchivo, 'full');  echo $src[0]; ?>" class="img-fluid"></a>
    				<h4><a href="<?php echo get_bloginfo('url').'/doctrina/'.$row->cont_slug; ?>"><?php echo $row->cont_titulo ?></a></h4>
    				<h5><?php echo $row->cont_subtitulo ?></a></h5>
    				<a href="#" class="pull-right save"><span class="glyphicon glyphicon-bookmark"></span></a>
    				<div class="stars">
    					<span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span>
    				</div>
    			</div>
    			-->
    			<div class="col-md-12 item">
    			    <h4><a href="<?php echo get_bloginfo('url').'/doctrina/'.$row->cont_slug; ?>"><?php echo $row->cont_titulo ?></a></h4>
    			    
    			    <p class="">
						<?php echo $row->cont_fecha?> <?php echo substr($row->cont_sumilla,0,200)."..." ?>
					</p>
    			</div>
				<?php 
				$i++;
				//if($i%6==0)echo '</div><div class="row">';
				endforeach;	?>
		</div>
		<?php elseif($this->tipo==3): ?>
		<div class="row">
			<div class="col-md-7">
				<h3>Modelos escritos <span><?php echo $total ?> resultados</span></h3>
			</div>
		</div>
		<div class="row">
			<?php
			$i=0;
					foreach($result as $row):
				?>
				<!--
				<div class="col-md-2 item">
    				<a href="<?php echo get_bloginfo('url').'/escritos/'.$row->cont_slug; ?>"><img src="<?php echo $row->cont_imagen; ?>" class="img-fluid"></a>
    				<h4><a href="<?php echo get_bloginfo('url').'/escritos/'.$row->cont_slug; ?>"><?php echo $row->cont_titulo ?></a></h4>
    				<h5><?php echo $row->cont_subtitulo ?></a></h5>
    				<a href="#" class="pull-right save"><span class="glyphicon glyphicon-bookmark"></span></a>
    				<div class="stars">
    					<span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span>
    				</div>
    			</div>
    			-->
    			<div class="col-md-12 item">
    			    <h4><a href="<?php echo get_bloginfo('url').'/escritos/'.$row->cont_slug; ?>"><?php echo $row->cont_titulo ?></a></h4>
    			    <p>
    			        <?php echo substr(strip_tags($row->cont_contenido),0,200)."..." ?>
    			    </p>
    			</div>
				<?php
				$i++; 
				//if($i%6==0)echo '</div><div class="row">';
				endforeach;	?>
		</div>
		<?php endif; ?>
		<?php echo doPages($nreg,$base,$turl, $total); ?>
	</div>
</div>