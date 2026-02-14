<div class="lis-search">
	<div class="container">
			<div class="row">
				<div class="col-md-7">
					<h3>Leyes <span><?php echo $normas_total ?> resultados</span></h3>
				</div>
				<div class="col-md-5 text-right">
					<br>
					<a href="<?php echo get_permalink(get_page_by_path('buscar-full'))?>?q=<?php echo $q; ?>" class="more">Ver más</a>
				</div>
			</div>
			<div class="row">
				<?php
					foreach($normas as $row):
				?>
					<div class="col-md-2 item">
						<h4><a href="<?php echo get_permalink( get_page_by_path('norma') ).$row->norm_id ?>"><?php echo $row->norm_titulo ?></a></h4>
						<p>
							<?php echo substr($row->norm_sumilla,0,100)."..." ?>
						</p>
						<p class="blue">Vigencia:
							<?php echo $row->norm_fecha?>
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
					<h3>Jurisprudencia <span><?php echo $juris_total ?> resultados</span></h3>
				</div>
				<div class="col-md-5 text-right">
					<br>
					<a href="<?php echo get_permalink(get_page_by_path('buscar-full'))?>?q=<?php echo $q; ?>" class="more">Ver más</a>
				</div>
			</div>
			<div class="row">
				<?php
					foreach($juris as $row):
				?>
					<div class="col-md-4 item">
						<h4><a href="<?php echo get_permalink( get_page_by_path('norma') ).$row->norm_url_firma ?>"><?php echo $row->norm_titulo ?></a></h4>
						<h5><?php echo $row->norm_nombre ?></a></h5>
						<a href="<?php echo get_permalink( get_page_by_path('norma') ).$row->norm_url_firma ?>"><img src="<?php echo get_template_directory_uri() ?>/img/jurisprudencia.jpg" class="alignleft"></a>
						<p>
							<?php echo substr($row->norm_sumilla,0,150)."..." ?>
						</p>
						<p class="blue">Vigencia:
							<?php echo $row->norm_fecha?>
						</p>
						<a href="#" class="pull-right save"><span class="glyphicon glyphicon-bookmark"></span></a>
						<div class="stars">
							<span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span>
						</div>
					</div>
				<?php endforeach;	?>
			</div>
	</div>
</div>