<div class="container diccionario">
		<div class="row">
			<div class="col-md-7">
				<h3>Diccionario jurídico <span><?php echo $total ?> resultados</span></h3>
			</div>
		</div>
		<div class="row">
		    <div class="col-md-12 link-word">    
    		    <?php foreach($lists as $l): 
    		        $class = (strtoupper($l) == strtoupper($current)) ? 'active' : '';
    				echo '<a class="'.$class.'" type="button" href="'. get_bloginfo('url').'/diccionario/'.$l.'">'. strtoupper($l) .'</a> ';
    			endforeach; ?>
    		</div>
		</div>
		<div class="row">
			<?php
			    $i = 0;
				foreach($result as $row):
				    //$letra = str_replace(" ", "-",$row->dicc_nombre);
				    $letra = urlencode($row->dicc_nombre);
			?>
			<div class="col-md-12 item-dicc">
				<a href="<?php echo get_bloginfo('url').'/diccionario/'.$letra ?>/"><h4><?php echo $row->dicc_nombre ?></h4></a>
				<p>
					<?php echo $row->dicc_descripcion ?>
				</p>
			</div>
			<?php $i++; if(($i % 4) == 0) echo "<div class='clearfix'></div>"; endforeach;	?>
		</div>
		<?php //echo doPages($nreg,$base,$turl, $total); ?>
</div>