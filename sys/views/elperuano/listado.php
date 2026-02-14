
<div class="container elperuano">
	<br>
	<div class="row">
		<div class="col-md-6">
			<img src="<?php echo get_template_directory_uri() ?>/img/elperuano.png" class="img-fluid">
			<p><strong><?php echo $strfecha; ?></strong></p>
		</div>
		<div class="col-md-6 ">
			<br>
			<br>
			<br>
			<p>Bajar cuadernillo de <?php echo $fecha ?> <a href="<?php echo $base.$row->elpe_pdf; ?>"><span class="fa-regular fa-file-pdf rojo"></span></a></p>
			<p><a href="<?php echo get_bloginfo('url').'/elperuano/calendario/' ?>">Ver el peruano de otras fechas <span class="fa fa-calendar"></span></a></p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<?php 
				$i=0;
				foreach($titulos as $key=>$result):
			?>
				<h3><?php echo $key; ?></h3>
				<?php foreach($result as $row): ?>
					<h4><a href="<?php echo get_bloginfo('url').'/elperuano/'.$row->deta_elpe_id.'-'.$row->deta_id.'/'.urlstring($row->deta_subtitulo); ?>"><?php echo $row->deta_subtitulo; ?>  <span class="fa fa-link"></span></a> <a href="<?php echo $base.$row->deta_pdf; ?>"><span class="fa-regular fa-file-pdf"></span></a></h4>
					<p><?php echo $row->deta_sumilla; ?></p>
				<?php endforeach; ?>
			<?php 
				if(floor(count($titulos)/2)==$i) echo '</div><div class="col-md-6">';
				$i++;
				endforeach; 
			?>
		</div>
	</div>

</div>