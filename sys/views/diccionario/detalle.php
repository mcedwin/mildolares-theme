<div class="container diccionario">
		<div class="row">
			<div class="col-md-7">
				<h3><span><?php echo $result->dicc_nombre ?></span></h3>
			</div>
		</div>
		<div class="row">
		    <div class="col-md-3">
		        <div class="shares text-center no-print">
		            <div class="social text-right">
		                <a href="<?php echo $turl; ?>" class="fb btn btn-circle"><span class="fa fa-facebook"></span></a>
                        <a href="<?php echo $turl; ?>" class="tw btn btn-circle"><span class="fa fa-twitter"></span></a>
                        <div class="dropdown dpwsocial" style="margin-right: 5px">
                            <button style="border: 0;" class="gp dropdown-toggle btn-circle" type="button" data-toggle="dropdown"><span class="fa fa-share-alt"></span></button>
                            <ul class="dropdown-menu">
                                <li><a href="https://api.whatsapp.com/send?text=<?= $turl ?>" target="_blank"><span class="fa fa-whatsapp"></span> WhatsApp</a></li>
                                <li><a href="mailto: ?subject=<?= $result->dicc_nombre ?>&body=<?= $result->dicc_descripcion ?>"><span class="fa fa-envelope"></span> Correo</a></li>
                            </ul>
                        </div>
						<span id="cte_i" class="hidden"><?= $result->dicc_id ?></span>
                        <?php getPostLikes(get_the_ID(),$result->dicc_id) ?>
		            </div>
		        </div>
		    </div>
			<div class="col-md-9">
				<h4>Descripcion</h4>
				<div class="subline"></div>
				<?php echo $result->dicc_descripcion ?>
				<?php echo $result->dicc_contenido ?>
			</div>
		</div>
</div>