<?php get_header(); ?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-10">

  <div class="md:col-span-2">

    <header class="mb-8 pb-4 border-b border-gray-200">
      <h1 class="text-3xl md:text-4xl font-bold mb-2">
        <?php the_archive_title(); ?>
      </h1>
      <?php if (get_the_archive_description()) : ?>
        <div class="text-gray-600"><?php the_archive_description(); ?></div>
      <?php endif; ?>
    </header>

    <?php if (have_posts()) : ?>

      <div class="space-y-8">
        <?php
        while (have_posts()) :
            the_post();
            $mil_terms = mil_post_terms(get_the_ID(), 1);
            $mil_link  = mil_first_term_link(get_the_ID());
            ?>

          <article class="flex flex-col sm:flex-row gap-5 pb-8 border-b border-gray-100 last:border-0">

            <?php if (has_post_thumbnail()) : ?>
              <a href="<?php the_permalink(); ?>" class="block shrink-0 w-full sm:w-48" tabindex="-1" aria-hidden="true">
                <?php the_post_thumbnail('medium', ['class' => 'rounded-lg w-full sm:h-32 object-cover', 'alt' => mil_thumbnail_alt(), 'loading' => 'lazy']); ?>
              </a>
            <?php endif; ?>

            <div class="flex-1 min-w-0">
              <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500 mb-2">
                <?php if ($mil_terms) : ?>
                  <a href="<?php echo esc_url($mil_link); ?>" class="font-semibold uppercase tracking-wide text-green-900 hover:underline">
                    <?php echo esc_html($mil_terms[0]->name); ?>
                  </a>
                  <span aria-hidden="true">&middot;</span>
                <?php endif; ?>
                <?php mil_date_line(); ?>
              </div>

              <h2 class="text-xl font-semibold leading-snug mb-2">
                <a href="<?php the_permalink(); ?>" class="hover:text-green-900 hover:underline transition">
                  <?php the_title(); ?>
                </a>
              </h2>

              <p class="text-sm leading-snug text-gray-700 mb-3">
                <?php echo esc_html(wp_trim_words(wp_strip_all_tags(get_the_excerpt()), 32, '…')); ?>
              </p>

              <span class="text-xs text-gray-500">
                <?php echo esc_html(mil_reading_time()); ?> <?php esc_html_e('min de lectura', 'mil'); ?>
                <?php if (mil_views_of()) : ?>
                  &middot; <?php echo esc_html(mil_number(mil_views_of(), 0)); ?> <?php esc_html_e('visitas', 'mil'); ?>
                <?php endif; ?>
              </span>
            </div>

          </article>

        <?php endwhile; ?>
      </div>

      <?php mildolares_paginador(); ?>

    <?php else : ?>

      <div class="text-center py-20 border border-dashed border-gray-300 rounded-xl bg-gray-50">
        <p class="text-gray-600"><?php esc_html_e('No hay entradas en este archivo.', 'mil'); ?></p>
      </div>

    <?php endif; ?>

  </div>

  <?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>
