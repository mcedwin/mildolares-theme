<?php
/*
Template Name: Full
*/
get_header();
?>
<div class="noticias pagina">
    <div class="row">
    <div class="col-md-12">
<article>
    <div class="pad15">
        <?
        the_post();
        ?>
        <h3><? the_title(); ?></h3>
        <?php the_content(); ?>
    </div>
    </article>
    </div>
</div>
<?php get_footer(); ?>

