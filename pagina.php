<?php /*
	template name: pagina
*/ ?><?php
get_header();
?>
<div class="container">
    <div class="row">
    <div class="col-md-12">
        <?
        the_post();
        ?>
        <div class="clearfix">
            <h2 class="mb10"><? the_title(); ?></h2>
            <?php the_content(); ?>
        </div>
    </div>
</div>
</div>
<?php get_footer(); ?>

