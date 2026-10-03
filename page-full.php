<?php
/*
Template Name: Full
*/
get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

  <article class="max-w-5xl mx-auto">

    <header class="mb-8 pb-6 border-b border-gray-200">
      <h1 class="text-3xl md:text-4xl font-bold leading-tight">
        <?php the_title(); ?>
      </h1>
      <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-gray-500 mt-4">
        <?php mil_date_line(); ?>
        <span aria-hidden="true">&middot;</span>
        <span><?php echo esc_html(mil_reading_time()); ?> <?php esc_html_e('min de lectura', 'mil'); ?></span>
      </div>
    </header>

    <div class="prose prose-lg max-w-none text-gray-800">
      <?php the_content(); ?>
    </div>

  </article>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
