<div class="wrap">
		<h2><?php echo "El Peruano ".$thisDate ?> <a href="<?php echo get_admin_base('elperuano','admin_ejecutar')."&fecha={$thisDate}"; ?>" class="page-title-action">Actualizar de servidor</a></h2>

		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-1">
				<div id="post-body-content">
					<div class="meta-box-sortables ui-sortable">
						<form method="get" action="admin.php">
							<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>">
							<?php
							$wptable->prepare_items();
							$wptable->display(); ?>
						</form>
					</div>
				</div>
			</div>
			<br class="clear">
		</div>
	</div>