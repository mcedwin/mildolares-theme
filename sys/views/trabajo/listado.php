<div class="container trabajo">
	<div class="row">
		<div class="col-md-5">
			<h3>Oferta laboral </h3>
		</div>
		<div class="col-md-7">
			<br>
			<div class="dropdown pull-right">
			  <button class="btn dropdown-toggle" type="button" data-toggle="dropdown"><?php echo $txtregion; ?>
			  <span class="caret"></span></button>
			  <ul class="dropdown-menu">
			  	<li><a href="?<?php echo (empty($empresa)?'':'empr='.$empresa); ?>">Todo el Perú</a></li>
			  	<?php foreach($regiones as $row): ?>
			    <li><a href="?<?php echo 'regi='.$row->regi_id.(empty($empresa)?'':'&empr='.$empresa); ?>"><?php echo $row->regi_nombre ?></a></li>
				<?php endforeach; ?>
			  </ul>
			</div>
			<div class="dropdown pull-right">
			  <button class="btn dropdown-toggle" type="button" data-toggle="dropdown"><?php echo $txtempresa; ?>
			  <span class="caret"></span></button>
			  <ul class="dropdown-menu">
			  	<li><a href="?<?php echo (empty($region)?'':'regi='.$region); ?>">Todas las Instituciones</a></li>
			    <?php foreach($empresas as $row): ?>
			    <li><a href="?<?php echo 'empr='.$row->empr_id.(empty($region)?'':'&regi='.$region); ?>"><?php echo $row->empr_nombre ?></a></li>
				<?php endforeach; ?>
			  </ul>
			</div>

		</div>
	</div>
	<br>
	<div class="row">
		<?php 
		$i=0;
		foreach($result as $row): ?>
		<div class="col-md-3">
			<div class="item">
				<h5><?php echo $row->empr_nombre ?></h5>
				<h4><?php echo $row->trab_titulo ?></h4>
				<p>	Valido hasta : <span><?php echo $row->trab_hasta ?></span> </p>
				<p>	Ubicación : <span><?php echo $row->trab_ubicacion ?></span> </p>
				<p>	Vacantes : <span><?php echo $row->trab_vacantes ?></span> </p>
				<p>	Presupuesto : <span><?php echo $row->trab_presupuesto ?></span> </p>
				<p>	Experiencia : <span><?php echo $row->trab_experiencia ?></span> </p>
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