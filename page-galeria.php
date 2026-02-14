<?php
/* Template Name: Page Galeria */
?>
<?php
get_header();
?>
<div class="noticias pagina">
    <div class="row">
    <div class="col-md-8">
    	<?php if (have_posts()) : while (have_posts()) : the_post();?>
	<article>
	    <div class="pad15">
	    <?php
		    ob_start();
		    the_content();
		    $content = ob_get_clean();
		    $galeria = get_post_gallery(get_the_ID(), false);
		    $galeria_ids = explode(',', $galeria['ids']);
		?>
	        <h3><? the_title(); ?></h3>
	    </div>

    	</article>
    	<div class="for-galeria">
	            <?php 
	              foreach ($galeria_ids as $key => $value):
	                $img_link = wp_get_attachment_image_src( $value, 'large' );
	                echo '<a data-fancybox-group="gallery" href="'.$img_link[0].'" rel="galeria_img" class="fancybox">'.wp_get_attachment_image( $value, 'thumbnail' ).'</a>';
	              endforeach;
	             ?>
	        </div>
	        	<?php endwhile; endif; ?>
    </div>
<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>


