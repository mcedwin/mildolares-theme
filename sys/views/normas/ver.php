<div class="container norma">
	<div class="row">
	    <div class="col-md-12">
	        <div class="shares text-center no-print">
                <div class="social text-right">
                    <a href="<?php echo get_bloginfo('url').'/normas/'.$row->recu_id.'-'.urlstring($row->recu_titulo); ?>" style="margin-right: 5px" class="fb btn-circle pull-left"><span class="fa fa-facebook"></span></a>
                    <a href="<?php echo get_bloginfo('url').'/normas/'.$row->recu_id.'-'.urlstring($row->recu_titulo); ?>" style="margin-right: 5px" class="tw btn-circle pull-left"><span class="fa fa-twitter"></span></a>
                    <div class="dropdown dpwsocial pull-left" style="margin-right: 5px">
                        <button style="border: 0;" class="gp dropdown-toggle btn-circle" type="button" data-toggle="dropdown"><span class="fa fa-share-alt"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="https://api.whatsapp.com/send?text=<?php echo get_bloginfo('url').'/normas/'.$row->recu_id.'-'.urlstring($row->recu_titulo); ?>" target="_blank" class="gp btn-circle pull-left"><span class="fa fa-whatsapp"></span> WhatsApp</a></li>
                        </ul>
                    </div>
                    <div class="dropdown dpwsocial pull-left">
					  	<button style="border: 0;" class="dw dropdown-toggle btn-circle" type="button" data-toggle="dropdown"><span class="fa fa-download"></span></button>
					  	<ul class="dropdown-menu">
					    	<a class="word-export" href="javascript:void(0)"><span class="fa fa-file-word-o"></span> Exportar a Word</a>
					  	</ul>
					</div>
					<span id="cte_i" class="hidden"><?= $row->recu_id ?></span>
                    <?php getPostStars(get_the_ID(),$row->recu_id) ?>
                </div>
            </div>
	    </div>
		<div class="col-md-12 text-justify" id="page-content">
			<?php echo (preg_match('/<p/i',$row->recu_contenido)?$row->recu_contenido:wpautop($row->recu_contenido));?>
		</div>
	</div>

</div>