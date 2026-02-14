<div class="container trabajo">
	<div class="row">
		<div class="col-md-5">
			<h3>Oferta académica </h3>
		</div>
		<div class="col-md-7">
			<br>
			<div class="dropdown pull-right">
			  <button class="btn dropdown-toggle" type="button" data-toggle="dropdown"><?php echo $txtregion; ?>
			  <span class="caret"></span></button>
			  <ul class="dropdown-menu">
			  	<li><a href="?<?php echo (empty($categoria)?'':'cate='.$categoria); ?>">Todo el Perú</a></li>
			  	<?php foreach($regiones as $row): ?>
			    <li><a href="?<?php echo 'regi='.$row->regi_id.(empty($categoria)?'':'&empr='.$categoria); ?>"><?php echo $row->regi_nombre ?></a></li>
				<?php endforeach; ?>
			  </ul>
			</div>

			<div class="dropdown pull-right">
			  <button class="btn dropdown-toggle" type="button" data-toggle="dropdown"><?php echo $txtcategoria; ?>
			  <span class="caret"></span></button>
			  <ul class="dropdown-menu">
			  	<li><a href="?<?php echo (empty($region)?'':'regi='.$region); ?>">Todas las categorías</a></li>
			    <?php foreach($categorias as $row): ?>
			    <li><a href="?<?php echo 'cate='.$row->cate_id.(empty($region)?'':'&regi='.$region); ?>"><?php echo $row->cate_nombre ?></a></li>
				<?php endforeach; ?>
			  </ul>
			</div>

		</div>
	</div>
	<br>
	<div class="row">
		<?php 
		$i = 0;
		foreach($result as $row): ?>
		<div class="col-md-3">
			<div class="item">
				<h5><?php echo $row->empr_nombre ?></h5>
				<h4><?php echo $row->acad_titulo ?></h4>
				<p>	Valido hasta : <span><?php echo $row->acad_hasta ?></span> </p>
				<p>	Ubicación : <span><?php echo $row->acad_ubicacion ?></span> </p>
				<p>	Vacantes : <span><?php echo $row->acad_vacantes ?></span> </p>
				<p>	Presupuesto : <span><?php echo $row->acad_presupuesto ?></span> </p>
				<p>	Nivel : <span><?php echo $row->acad_nivel ?></span> </p>
				<p>	Modalidad : <span><?php echo $row->acad_modalidad ?></span> </p>
				<div class="text-center"><img src="<?php echo $row->empr_logo; ?>" class="img-fluid"></div>
			</div>
		</div>
		<?php 
		$i++;
		if($i%4==0)echo '</div><div class="row">';
		endforeach;	?>
	</div>
	<?php echo doPages($nreg,$base,$turl, $total); ?>
</div>