<?php get_header(); ?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-10">

  <!-- COLUMNA PRINCIPAL -->
  <div class="md:col-span-2">

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

      <article>

        <?php
        $mil_terms  = mil_post_terms(get_the_ID(), 3);
        $mil_toc   = mil_toc(get_the_content());
        $mil_share = rawurlencode(get_permalink());
        $mil_title = rawurlencode(get_the_title());
        ?>

        <!-- Migas de pan -->
        <nav aria-label="<?php esc_attr_e('Ruta de navegación', 'mil'); ?>" class="text-sm text-gray-500 mb-4">
          <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-green-900"><?php esc_html_e('Inicio', 'mil'); ?></a>
          <?php if ($mil_terms) : ?>
            <span aria-hidden="true">/</span>
            <a href="<?php echo esc_url(get_category_link($mil_terms[0]->term_id)); ?>" class="hover:text-green-900">
              <?php echo esc_html($mil_terms[0]->name); ?>
            </a>
          <?php endif; ?>
        </nav>

        <!-- Categorías -->
        <?php if (count($mil_terms) > 1) : ?>
          <div class="flex flex-wrap gap-2 mb-3">
            <?php foreach (array_slice($mil_terms, 1) as $mil_term) : ?>
              <a href="<?php echo esc_url(get_category_link($mil_term->term_id)); ?>"
                 class="text-xs font-semibold uppercase tracking-wide text-green-900 bg-green-50 px-3 py-1 rounded-full hover:bg-green-100 transition">
                <?php echo esc_html($mil_term->name); ?>
              </a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <!-- Título -->
        <h1 class="text-3xl md:text-4xl font-bold mb-4 leading-tight">
          <?php the_title(); ?>
        </h1>

        <!-- Meta -->
        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-gray-500 pb-6 mb-8 border-b border-gray-200">
          <span class="font-medium text-gray-700"><?php the_author(); ?></span>
          <span aria-hidden="true">&middot;</span>
          <?php mil_date_line(); ?>
          <span aria-hidden="true">&middot;</span>
          <span><?php echo esc_html(mil_reading_time()); ?> <?php esc_html_e('min de lectura', 'mil'); ?></span>
          <?php if (mil_views_of()) : ?>
            <span aria-hidden="true">&middot;</span>
            <span><?php echo esc_html(mil_number(mil_views_of(), 0)); ?> <?php esc_html_e('visitas', 'mil'); ?></span>
          <?php endif; ?>
        </div>

        <!-- Imagen destacada -->
        <?php if (has_post_thumbnail()) : ?>
          <figure class="mb-8">
            <?php the_post_thumbnail('large', array(
                'class'   => 'rounded-xl w-full',
                'alt'     => mil_thumbnail_alt(),
                'loading' => 'eager',
            )); ?>
          </figure>
        <?php endif; ?>

        <!-- Índice -->
        <?php if ($mil_toc) : ?>
          <?php echo $mil_toc; ?>
        <?php endif; ?>

        <!-- Contenido -->
        <div class="prose prose-lg max-w-none text-gray-800 prose-h2:mt-6 prose-h2:mb-2 prose-ul:my-3 prose-li:my-1">
          <?php the_content(); ?>
        </div>

        <!-- Compartir -->
        <div class="mt-10 pt-6 border-t border-gray-200">
          <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500 mb-3">
            <?php esc_html_e('Compartir', 'mil'); ?>
          </h2>
          <div class="flex flex-wrap gap-2 text-sm">
            <a class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-green-800 hover:text-white transition"
               href="https://twitter.com/intent/tweet?url=<?php echo esc_attr($mil_share); ?>&text=<?php echo esc_attr($mil_title); ?>"
               target="_blank" rel="noopener noreferrer">X</a>
            <a class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-green-800 hover:text-white transition"
               href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_attr($mil_share); ?>"
               target="_blank" rel="noopener noreferrer">Facebook</a>
            <a class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-green-800 hover:text-white transition"
               href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo esc_attr($mil_share); ?>"
               target="_blank" rel="noopener noreferrer">LinkedIn</a>
            <a class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-green-800 hover:text-white transition"
               href="https://wa.me/?text=<?php echo esc_attr($mil_title . ' ' . $mil_share); ?>"
               target="_blank" rel="noopener noreferrer">WhatsApp</a>
          </div>
        </div>

        <!-- Etiquetas -->
        <?php if (has_tag()) : ?>
          <div class="mt-8 flex flex-wrap gap-2 text-sm">
            <?php the_tags('', '', ''); ?>
          </div>
        <?php endif; ?>

      </article>

    <?php endwhile; endif; ?>

    <!-- Relacionados -->
    <?php
    $mil_related_args = array(
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => 3,
        'ignore_sticky_posts' => true,
        'no_found_rows'       => true,
    );

    $mil_cat_ids = $mil_terms ? wp_list_pluck($mil_terms, 'term_id') : array();

    if ($mil_cat_ids) {
        $mil_related_args['category__in'] = $mil_cat_ids;
    } else {
        $mil_related_args['post__not_in'] = array(get_the_ID());
    }

    $mil_related = new WP_Query($mil_related_args);
    ?>

    <?php if ($mil_related->have_posts()) : ?>
      <section class="mt-14 pt-8 border-t border-gray-200">
        <h2 class="text-2xl font-bold mb-6"><?php esc_html_e('Te puede interesar', 'mil'); ?></h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
          <?php
          while ($mil_related->have_posts()) :
              $mil_related->the_post();
              $mil_thumb = has_post_thumbnail();
              ?>
              <article class="<?php echo $mil_thumb ? 'flex flex-col' : 'flex flex-col h-full rounded-xl border border-green-100 bg-green-50 p-5 transition hover:border-green-300'; ?>">
                <?php if ($mil_thumb) : ?>
                  <a href="<?php the_permalink(); ?>" class="block mb-3" tabindex="-1" aria-hidden="true">
                    <?php echo mil_post_thumbnail('medium', 'rounded-lg w-full', 'aspect-[3/2]'); ?>
                  </a>
                <?php else : ?>
                  <span class="block w-10 h-1.5 rounded-full bg-green-800 mb-4" aria-hidden="true"></span>
                <?php endif; ?>
                <h3 class="text-lg font-semibold leading-snug mb-2">
                  <a href="<?php the_permalink(); ?>" class="hover:text-green-900 hover:underline transition">
                    <?php the_title(); ?>
                  </a>
                </h3>
                <p class="text-sm text-gray-600">
                  <?php echo esc_html(wp_trim_words(wp_strip_all_tags(get_the_excerpt()), 20, '…')); ?>
                </p>
              </article>
              <?php
          endwhile;
          wp_reset_postdata();
          ?>
        </div>
      </section>
    <?php endif; ?>

    <!-- Navegación -->
    <nav class="mt-14 pt-8 border-t border-gray-200 grid grid-cols-1 sm:grid-cols-2 gap-4" aria-label="<?php esc_attr_e('Navegación entre artículos', 'mil'); ?>">
      <div class="text-sm">
        <?php
        $mil_prev = get_previous_post();
        if ($mil_prev) :
            ?>
            <span class="text-gray-500 block mb-1"><?php esc_html_e('Anterior', 'mil'); ?></span>
            <a href="<?php echo esc_url(get_permalink($mil_prev)); ?>" class="font-medium hover:text-green-900">
              <?php echo esc_html(get_the_title($mil_prev)); ?>
            </a>
        <?php endif; ?>
      </div>
      <div class="text-sm sm:text-right">
        <?php
        $mil_next = get_next_post();
        if ($mil_next) :
            ?>
            <span class="text-gray-500 block mb-1"><?php esc_html_e('Siguiente', 'mil'); ?></span>
            <a href="<?php echo esc_url(get_permalink($mil_next)); ?>" class="font-medium hover:text-green-900">
              <?php echo esc_html(get_the_title($mil_next)); ?>
            </a>
        <?php endif; ?>
      </div>
    </nav>

  </div>

  <?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>