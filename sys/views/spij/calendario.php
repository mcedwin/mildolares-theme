<a href="<?php echo get_bloginfo('url').'/spij/bot/0'; ?>/?inicio=false&bre=0" target="miframe">Sacar contenido de nuevos</a>
<iframe style="width:100%; height:400px;" name="miframe">

</iframe>
<style>
	.col-sm-4{
		width: 33%;
		float:left;
	}
	.col-md-4{
		width: 33%;
		float:left;
	}
	.row {
    overflow: auto;
}
	.row::after {
    content: "";
    clear: both;
    display: table;
}
</style>
<div class="container elperuano">
<div class="row">
<div class="col-md-4">
	<br>
	<a href="<?php echo get_admin_base('spij','admin_calendario') ?>&anio=<?php echo ($anio-1) ?>" class="btn btn-success"><?php echo ($anio-1) ?></a>
</div>
<div class="col-md-4 text-center">
<h2>Calendario <?=$anio?></h2>
</div>
<div class="col-md-4 text-right">
	<br>
<?php $next = (($anio+1)>date('Y')*1?'':($anio+1)); ?>
<?php if($next>0){ ?>
	<a href="<?php echo get_admin_base('spij','admin_calendario') ?>&anio=<?php echo $next; ?>" class="btn btn-success"><?php echo $next ?></a>
<?php } ?>
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
								if($time<=time()){
									$cfecha = date("Y-m-d",$time);
									$rw = $this->db->get_row("SELECT recu_id FROM spij_recurso WHERE recu_fecha='{$cfecha}' LIMIT 1");
									$style = '';
									if(isset($rw->recu_id)) $style = 'style="color:red;"';
							?>
								<a href="<?php echo get_bloginfo('url').'/spij/getdia/'.$cfecha ?>/?inicio=false&bre=0" <?php echo $style; ?> target="miframe"><?php echo  $days[$i]; ?></a>
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