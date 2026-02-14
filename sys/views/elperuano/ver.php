<div class="container elperuano">
  <?php
  $uploads = wp_upload_dir();
  $base = $uploads['baseurl'] . "/elperuano/";

  $cuaderno =  $base . "cuad_" . $row->deta_elpe_id . ".pdf";

  $permalink = get_bloginfo('url') . '/elperuano/' . $row->deta_elpe_id . '-' . $row->deta_id . '/' . urlstring($row->deta_subtitulo);
  $pdf = $base . $row->deta_pdf;
  $tipo = get_the_ID();

  ?>
  <div class="row single">
    <div class="col-md-12">
      <img class="img-elperuano" src="<?php echo get_template_directory_uri() ?>/img/el_peruano.png">
      <div class="shares no-print d-flex justify-content-between">

        <div class="social ">
          <a href="<?php echo $permalink; ?>" style="margin-right: 5px" class="rounded-circle fb"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="<?php echo $permalink; ?>" style="margin-right: 5px" class="rounded-circle tw"><i class="fa-brands fa-twitter"></i></a>
          <a href="<?php echo $cuaderno; ?>" style="margin-right: 5px" class="rounded-circle az"><i class="fa-solid fa-folder-open"></i></a></a>
          <a href="<?php echo $pdf; ?>" style="margin-right: 5px" class="rounded-circle gr"><i class="fa-solid fa-file-pdf"></i></a></a>
        </div>
        <div>
          <?php getPostLikes($tipo, $row->deta_codref) ?>
        </div>

      </div>
    </div>
    <div class="col-md-12 text-center">
      <h1><?php echo $row->deta_sumilla ?></h1>
    </div>
    <!--
		<div class="col-md-2">
			<div class="shares text-center no-print">
                <div class="social">
                    <a href="<?php echo $permalink; ?>" class="fb btn-circle"><span class="fa fa-facebook"></span></a>
                    <a href="<?php echo $permalink; ?>" class="tw btn-circle"><span class="fa fa-twitter"></span></a>
                    <a href="<?php echo $permalink; ?>" class="gr btn-circle"><span class="fa fa-print"></span></a>
                </div>
            </div>
            <div class="shares text-center no-print">

                    <p><a href="<?php echo $cuaderno; ?>" ><span class="fa fa-file-pdf-o rojo"></span> Cuadernillo</a></p>
                    <p><a href="<?php echo $pdf; ?>" class=""><span class="fa fa-file-pdf-o rojo"></span> Individual</a></p>
    
            </div>
		</div>
		-->
    <div class="">
      <div class="text-justify contenido">
        <?php
        if (preg_match("#<p>#", $row->deta_contenido)) echo $row->deta_contenido;
        else echo wpautop($row->deta_contenido);
        ?>
      </div>
    </div>
  </div>
</div>