<div class="container diccionario">
	<div class="row">
		<div class="col-md-7">
			<h3>Diccionario jurídico </h3>
		</div>
		<div class="col-md-5 text-right">
			<br>
			
		</div>
		<div class="col-md-12">
			<form class="form-inline" action="<?php echo get_bloginfo('url').'/diccionario' ?>">
				<div class="form-group">
				    <input type="text" class="form-control" name="se">
				</div>
				<button type="submit" class="btn btn-primary">Buscar</button>
					<?php foreach($lists as $l=>$list): 
						echo '<a class="link-word" type="button" href="'. get_bloginfo('url').'/diccionario/'.$l.'">'. strtoupper($l) .'</a> ';
					endforeach; ?>
			</form>
		</div>		
	</div>
	<?php foreach($lists as $l=>$list): if(COUNT($list) > 0): ?>
	<div class="row">
		<a href="<?php echo get_bloginfo('url').'/diccionario/'.$l ?>/" class="more"> &gt;&gt;&gt; </span>
		</a>
	</div>
	<div class="row">
		<?php 
		$i=0;
		foreach($list as $row): 
		//$palabra = str_replace(" ", "-", $row->dicc_nombre); 
		$palabra = urlencode($row->dicc_nombre);
		?>
		<div class="col-md-3 item">
			<a href="<?php echo get_bloginfo('url').'/diccionario/'.$palabra ?>/"><h4><?php echo $row->dicc_nombre ?></h4></a>
			<div class="subline"></div>
			<p>
				<?php echo $row->dicc_descripcion ?>
			</p>
		</div>
		<?php 
		$i++;
		//if($i%4==0)echo '</div><div class="row">';
		endforeach;	
		?>
	</div>
	
	<?php endif; endforeach; ?>
</div>