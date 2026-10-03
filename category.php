<?php get_header(); ?>

  <!-- Título de categoría -->
  <header class="mb-6 pb-4 border-b border-gray-200">

    <h1 class="text-4xl font-bold mb-4">
      <?php single_cat_title(); ?>
    </h1>

    <?php if (category_description()) : ?>
      <div class="text-gray-600 text-lg">
        <?php echo category_description(); ?>
      </div>
    <?php endif; ?>

  </header>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

    <!-- COLUMNA PRINCIPAL -->
    <div class="md:col-span-2">

      <?php if (have_posts()) : ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

          <?php while (have_posts()) : the_post(); ?>

            <article class="flex flex-col">
              <?php if (has_post_thumbnail()) : ?>
                <a href="<?php the_permalink(); ?>" class="block mb-3" tabindex="-1" aria-hidden="true">
                  <?php the_post_thumbnail('medium', ['class' => 'rounded-lg w-full', 'alt' => mil_thumbnail_alt(), 'loading' => 'lazy']); ?>
                </a>
              <?php endif; ?>

              <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                <?php mil_date_line(); ?>
                <span aria-hidden="true">&middot;</span>
                <span><?php echo esc_html(mil_reading_time()); ?> <?php esc_html_e('min de lectura', 'mil'); ?></span>
              </div>

              <h2 class="text-lg font-semibold leading-snug mb-2">
                <a href="<?php the_permalink(); ?>" class="hover:text-green-900 hover:underline transition">
                  <?php the_title(); ?>
                </a>
              </h2>

              <p class="text-sm leading-snug text-gray-700 mt-auto">
                <?php echo esc_html(wp_trim_words(wp_strip_all_tags(get_the_excerpt()), 25, '…')); ?>
              </p>
            </article>

          <?php endwhile; ?>

        </div>

        <!-- PAGINADOR -->
        <?php mildolares_paginador(); ?>

      <?php else : ?>

        <div class="text-center py-16 border border-dashed border-gray-300 rounded-xl bg-gray-50">
          <p class="text-gray-600"><?php esc_html_e('No hay artículos en esta categoría.', 'mil'); ?></p>
        </div>

      <?php endif; ?>

    </div>

    <?php get_sidebar(); ?>

  </div>

<?php get_footer(); ?>