<?php get_header(); ?>

<div class="max-w-site mx-auto px-6">

  <!-- Título de categoría -->
  <header class="mb-16">

    <h1 class="text-4xl font-bold mb-4">
      <?php single_cat_title(); ?>
    </h1>

    <?php if (category_description()) : ?>
      <div class="text-gray-600 text-lg">
        <?php echo category_description(); ?>
      </div>
    <?php endif; ?>

    <div class="border-t border-gray-200 mt-10"></div>

  </header>

  <div class="grid grid-cols-1 md:grid-cols-4 gap-12">

    <!-- COLUMNA PRINCIPAL -->
    <div class="md:col-span-3">

      <?php if (have_posts()) : ?>

        <div class="space-y-14">

        <?php while (have_posts()) : the_post(); ?>

          <article>

            <h2 class="text-2xl font-semibold mb-3">
              <a href="<?php the_permalink(); ?>" class="hover:underline">
                <?php the_title(); ?>
              </a>
            </h2>

            <div class="text-sm text-gray-500 mb-4">
              <?php the_time('F j, Y'); ?>
            </div>

            <?php if (has_post_thumbnail()) : ?>
              <div class="mb-5">
                <?php the_post_thumbnail('medium_large', ['class' => 'rounded-lg w-full']); ?>
              </div>
            <?php endif; ?>

            <div class="text-gray-800 leading-relaxed">
              <?php the_excerpt(); ?>
            </div>

          </article>

          <div class="border-t border-gray-100"></div>

        <?php endwhile; ?>

        </div>

        <!-- Paginación -->
        <div class="mt-16">
          <?php the_posts_pagination([
              'prev_text' => '← Anterior',
              'next_text' => 'Siguiente →',
              'class' => 'text-sm'
          ]); ?>
        </div>

      <?php else : ?>

        <p>No hay artículos en esta categoría.</p>

      <?php endif; ?>

    </div>

     <?php get_sidebar(); ?>

  </div>

</div>

<?php get_footer(); ?>