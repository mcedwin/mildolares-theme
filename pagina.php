<?php
/*
Template Name: Pagina
*/
get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

  <article class="max-w-4xl mx-auto">

    <h1 class="text-3xl md:text-4xl font-bold mb-6 leading-tight">
      <?php the_title(); ?>
    </h1>

    <?php if (has_post_thumbnail()) : ?>
      <figure class="mb-8">
        <?php the_post_thumbnail('large', ['class' => 'rounded-xl w-full', 'alt' => mil_thumbnail_alt(), 'loading' => 'eager']); ?>
      </figure>
    <?php endif; ?>

    <div class="prose prose-lg max-w-none text-gray-800">
      <?php the_content(); ?>
    </div>

  </article>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
