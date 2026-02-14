<a href="<?php echo get_admin_base('legislacion','admin_logs').'&id='.($id+1)?>" style="float:right; font-size:30px; font-weight:bold;">Siguiente</a>
<a href="<?php echo get_admin_base('legislacion','admin_logs').'&id='.($id-1)?>" style="font-size:30px; font-weight:bold;">Anterior</a>
<?php echo wpautop($row->contenido) ?>