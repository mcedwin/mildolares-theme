<div class="wrap">
		<h2><?php echo $this->titulos[$this->tipo][0] ?> <a href="<?php echo get_admin_base($this->getclass(),'admin_crear'); ?>" class="page-title-action">Añadir</a></h2>

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