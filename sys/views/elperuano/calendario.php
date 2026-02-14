<div class="container elperuano">
<div class="row">
<div class="col-md-4">
	<br>
    <?= $button_left ?>
</div>
<div class="col-md-4 text-center">
    <h2>Calendario <?=$anio?></h2>
</div>
<div class="col-md-4 text-right">
	<br>
	<!--
<?php $next = (($anio+1)>date('Y')*1?'':($anio+1)); ?>
<?php if($next>0){ ?>
	<a href="<?php echo get_bloginfo('url').'/elperuano/calendario/'.$next; ?>" class="btn btn-success"><?php echo $next ?></a>
<?php } ?>
-->
<?= $button_right ?>
</div>
</div>
<div class="row">
<?php
	$meses = array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		for($c=1;$c<=12;$c++){
			$week = 1;
			$mes = $c;
			$calendar = array();
			$time = strtotime($anio.'-'.$mes.'-'.'1');
			for($i=1;$i<=date('t',$time);$i++) {
				$time = strtotime($anio.'-'.$mes.'-'.$i);
				$day_week = date('N', $time);
				$smes = $meses[$c];
				
				$calendar[$week][$day_week] = $i;
				if ($day_week == 7) { $week++; };
			}
		?>
			<div class="caja <?php echo ($c%3==0?'fin':'')?> col-sm-4">
			<table class="table">
			<thead>
				<tr>
					<th colspan="7" class="tht"><?php echo $smes ?></th>
				</tr>
				<tr>
					<th>Lun</th>
					<th>Mar</th>   
					<th>Mié</th>   
					<th>Jue</th>   
					<th>Vie</th>   
					<th>Sáb</th>   
					<th>Dom</th>   
				</tr>
			</thead>
			<tbody>
				<?php foreach ($calendar as $days) : ?>
					<tr>
						<?php for ($i=1;$i<=7;$i++) : ?>
							<td>
							<?php if(isset($days[$i])){ 
								$time = strtotime($anio.'-'.$mes.'-'.$days[$i]);
								if($time<=time()&&isset($rs[$mes*1][$days[$i]*1])){
							?>
								<a href="<?php echo get_bloginfo('url').'/elperuano/listado/'.date("Y-m-d",$time) ?>"><?php echo  $days[$i]; ?></a>
							<?php }else{
								echo  $days[$i];
							}
							
							} ?>
							</td>
						<?php endfor; ?>
					</tr>
				<?php endforeach ?>
			</tbody>
			</table>
			</div>
		<?php
			if($c%3==0)echo '</div><div class="row">';
		}
	?>
</div>	
</div>