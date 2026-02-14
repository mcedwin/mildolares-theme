<div class="container norma">
	<div class="row">
		<div class="col-md-12">
			<div class="shares text-center no-print">
				<div class="social text-right">
					<a href="<?php echo get_bloginfo('url') . '/' . $this->getclass() . '/' . $row->cont_id . '-' . urlstring($row->cont_titulo); ?>" style="margin-right: 5px" class="fb btn-circle pull-left"><span class="fa fa-facebook"></span></a>
					<a href="<?php echo get_bloginfo('url') . '/' . $this->getclass() . '/' . $row->cont_id . '-' . urlstring($row->cont_titulo); ?>" style="margin-right: 5px" class="tw btn-circle pull-left"><span class="fa fa-twitter"></span></a>
					<div class="dropdown dpwsocial pull-left" style="margin-right: 5px">
						<button style="border: 0;" class="gp dropdown-toggle btn-circle" type="button" data-toggle="dropdown"><span class="fa fa-share-alt"></span></button>
						<ul class="dropdown-menu">
							<li><a href="https://api.whatsapp.com/send?text=<?php echo get_bloginfo('url') . '/' . $this->getclass() . '/' . $row->cont_id . '-' . urlstring($row->cont_titulo); ?>" target="_blank" class="gp btn-circle pull-left"><span class="fa fa-whatsapp"></span> WhatsApp</a></li>
						</ul>
					</div>
					<div class="dropdown dpwsocial pull-left">
						<button style="border: 0;" class="dw dropdown-toggle btn-circle" type="button" data-toggle="dropdown"><span class="fa fa-download"></span></button>
						<ul class="dropdown-menu">
							<?php if ($tipo != 3) :  ?>
								<a target="_blank" href="<?php echo $row->cont_archivo; ?>"><span class="fa fa-file-pdf-o"></span> Descargar</a>
							<?php else : ?>
								<a target="_blank" class="word-export" href="javascript:void(0)"><span class="fa fa-file-word-o"></span> Exportar a Word</a>
							<?php endif; ?>
						</ul>
					</div>
					<span id="cte_i" class="hidden"><?= $row->cont_id ?></span>
					<?php getPostStars(get_the_ID(), $row->cont_id) ?>
				</div>
			</div>
		</div>
		<div class="col-md-12 text-justify" id="page-content">
			<?php if ($tipo != 3) :  ?>
				<h3><?php echo $row->cont_titulo; ?></h2>
				<?php endif; ?>
				<div id="caja-indice" class="d-none">
					<h1>Índice</h1>
					<!-- <hr> -->
					<div id="indice">

					</div>
				</div>
				<div id="cuerpo">
				<?php 

				function toolreplace($con){
					global $wpdb;
					$title = str_replace("[#]","\"",$con[2]);
					$fecha = $con[1];
					$fecha = empty($fecha)?"":"<br><strong>Fecha de publicación</strong>: ".$fecha;
					//$cate = $con[2];
					//$link = $con[1];
					/*if(strlen($link)>0){
						preg_match("#".get_bloginfo('url').".+?/([0-9]+)-#",$link,$arr);*/
						//die("#".get_bloginfo('url').".+?/([0-9])+-#");
						/*if(isset($arr[0])){
							$id = $arr[1];
							$row = $wpdb->get_row("SELECT * FROM sys_contenido WHERE cont_id='{$id}'");
							//die("SELECT * FROM sys_contenido WHERE cont_id='{$id}'"); 
							$link = "<br><br>Enlace : <a href=\"{$link}\">{$row->cont_titulo}</a>";
						}else{
							$link = "<br><br>Enlace externo : <a href=\"{$link}\">Link</a>";
						}
						$link = ($link);*/
					//}
					
					$name = $con[3];
					return "<a href='#' class='super' data-toggle='popover' title='{$name}' data-placement='bottom' data-content='{$title}{$fecha}'>{$name}</a>";
					//return "<a href='#' class='my-tooltip super' data-toggle='tooltip' data-placement='bottom' data-html='true' title='{$title}{$link}'>{$name}</a>";
				}

				$data = wpautop($row->cont_contenido);
				$data = preg_replace_callback('#<span class="relam" data-f="(.*?)" data-v="(.*?)">(.+?)</span>#is',"toolreplace",$data);
				echo $data;
				 ?>
				</div>
		</div>
	</div>

</div>