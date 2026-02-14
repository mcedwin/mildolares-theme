<?php get_header(); ?>

<div class="max-w-site mx-auto px-6">

  <h1 class="text-3xl font-bold mb-10">
    Resultados para: "<?php echo get_search_query(); ?>"
  </h1>

  <?php if (have_posts()) : ?>

    <?php while (have_posts()) : the_post(); ?>

      <article class="mb-12">

        <h2 class="text-xl font-semibold mb-2">
          <a href="<?php the_permalink(); ?>" class="hover:underline">
            <?php the_title(); ?>
          </a>
        </h2>

        <div class="text-sm text-gray-500 mb-3">
          <?php the_time('F j, Y'); ?>
        </div>

        <?php the_excerpt(); ?>

      </article>

    <?php endwhile; ?>

  <?php else : ?>

    <p>No se encontraron resultados.</p>

  <?php endif; ?>

</div>

<?php get_footer(); ?>