<?php get_header(); ?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-10">

  <div class="md:col-span-2">

    <header class="mb-8 pb-6 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center gap-5">
      <?php echo get_avatar(get_the_author_meta('ID'), 80, '', get_the_author(), ['class' => 'rounded-full shrink-0']); ?>
      <div>
        <h1 class="text-3xl font-bold mb-1"><?php the_author(); ?></h1>
        <?php if (get_the_author_meta('description')) : ?>
          <p class="text-gray-600 text-sm max-w-2xl"><?php echo esc_html(get_the_author_meta('description')); ?></p>
        <?php endif; ?>
        <p class="text-xs text-gray-500 mt-1">
          <?php printf(esc_html(_n('%d artículo', '%d artículos', (int) $wp_query->found_posts, 'mil'), (int) $wp_query->found_posts), (int) $wp_query->found_posts); ?>
        </p>
      </div>
    </header>

    <?php if (have_posts()) : ?>

      <div class="space-y-8">
        <?php
        while (have_posts()) :
            the_post();
            $mil_terms = mil_post_terms(get_the_ID(), 1);
            $mil_link  = mil_first_term_link(get_the_ID());
            $mil_thumb = has_post_thumbnail();
            ?>

          <article class="flex flex-col sm:flex-row gap-5 pb-8 border-b border-b-gray-100 last:border-b-0<?php echo $mil_thumb ? '' : ' border-l-4 border-l-green-800 pl-4 sm:pl-5 rounded-r-lg'; ?>">

            <?php if ($mil_thumb) : ?>
              <a href="<?php the_permalink(); ?>" class="block shrink-0 w-full sm:w-48" tabindex="-1" aria-hidden="true">
                <?php echo mil_post_thumbnail('medium', 'rounded-lg w-full', 'aspect-[3/2] sm:aspect-auto sm:h-32'); ?>
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
              </span>
            </div>

          </article>

        <?php endwhile; ?>
      </div>

      <?php mildolares_paginador(); ?>

    <?php else : ?>

      <div class="text-center py-20 border border-dashed border-gray-300 rounded-xl bg-gray-50">
        <p class="text-gray-600"><?php esc_html_e('Este autor aún no tiene artículos publicados.', 'mil'); ?></p>
      </div>

    <?php endif; ?>

  </div>

  <?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>
