<?php get_header(); ?>

<?php
$mil_destacado = null;

if (! is_paged() && have_posts()) {
    the_post();
    $mil_destacado = get_post();
}

$mil_cat_destacado = $mil_destacado ? mil_post_terms($mil_destacado->ID, 1) : array();
$mil_link_destacado = $mil_destacado ? mil_first_term_link($mil_destacado->ID) : '';
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-10">

  <!-- COLUMNA PRINCIPAL -->
  <div class="md:col-span-2">

    <h1 class="sr-only"><?php echo esc_html(get_bloginfo('name') . ' — ' . mil_site_description()); ?></h1>

    <?php if ($mil_destacado) : ?>

      <!-- NOTICIA DESTACADA -->
      <article class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start pb-10 border-b border-gray-200">

        <a href="<?php echo esc_url(get_permalink($mil_destacado)); ?>" class="block" tabindex="-1" aria-hidden="true">
          <?php
          echo get_the_post_thumbnail($mil_destacado->ID, 'large', array(
              'class'   => 'rounded-xl w-full',
              'alt'     => mil_thumbnail_alt($mil_destacado->ID),
              'loading' => 'eager',
          ));
          ?>
        </a>

        <div>
          <?php if ($mil_cat_destacado) : ?>
            <a href="<?php echo esc_url($mil_link_destacado); ?>"
               class="inline-block text-xs font-semibold uppercase tracking-wide text-green-900 bg-green-50 px-3 py-1 rounded-full mb-4 hover:bg-green-100 transition">
              <?php echo esc_html($mil_cat_destacado[0]->name); ?>
            </a>
          <?php endif; ?>

          <h2 class="text-3xl font-bold leading-tight mb-4">
            <a href="<?php echo esc_url(get_permalink($mil_destacado)); ?>" class="hover:text-green-900 hover:underline transition">
              <?php echo esc_html(get_the_title($mil_destacado)); ?>
            </a>
          </h2>

          <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-gray-500 mb-4">
            <?php mil_date_line(); ?>
            <span aria-hidden="true">&middot;</span>
            <span><?php echo esc_html(mil_reading_time($mil_destacado)); ?> <?php esc_html_e('min de lectura', 'mil'); ?></span>
          </div>

          <p class="text-lg text-gray-700 leading-relaxed mb-6">
            <?php echo esc_html(wp_trim_words(wp_strip_all_tags(get_the_excerpt($mil_destacado)), 45, '…')); ?>
          </p>

          <a href="<?php echo esc_url(get_permalink($mil_destacado)); ?>"
             class="inline-block bg-green-800 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-900 transition">
            <?php esc_html_e('Leer artículo', 'mil'); ?> &rarr;
          </a>
        </div>

      </article>

    <?php endif; ?>

    <!-- GRID DE NOTICIAS -->
    <?php if (have_posts()) : ?>

      <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mt-10">

        <?php
        while (have_posts()) :
            the_post();
            $mil_terms = mil_post_terms(get_the_ID(), 1);
            $mil_link  = mil_first_term_link(get_the_ID());
            ?>

          <article class="flex flex-col">

            <?php if (has_post_thumbnail()) : ?>
              <a href="<?php the_permalink(); ?>" class="block mb-3" tabindex="-1" aria-hidden="true">
                <?php
                the_post_thumbnail('medium', array(
                    'class'   => 'rounded-lg w-full',
                    'alt'     => mil_thumbnail_alt(),
                    'loading' => 'lazy',
                ));
                ?>
              </a>
            <?php endif; ?>

            <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500 mb-2">
              <?php if ($mil_terms) : ?>
                <a href="<?php echo esc_url($mil_link); ?>" class="font-semibold uppercase tracking-wide text-green-900 hover:underline">
                  <?php echo esc_html($mil_terms[0]->name); ?>
                </a>
                <span aria-hidden="true">&middot;</span>
              <?php endif; ?>
              <?php mil_date_line(); ?>
            </div>

            <h3 class="text-xl font-semibold leading-snug mb-2">
              <a href="<?php the_permalink(); ?>" class="hover:text-green-900 hover:underline transition">
                <?php the_title(); ?>
              </a>
            </h3>

            <p class="text-gray-700 leading-snug mb-4">
              <?php echo esc_html(wp_trim_words(wp_strip_all_tags(get_the_excerpt()), 28, '…')); ?>
            </p>

            <div class="mt-auto text-sm text-gray-500">
              <?php echo esc_html(mil_reading_time()); ?> <?php esc_html_e('min de lectura', 'mil'); ?>
            </div>

          </article>

        <?php endwhile; ?>

      </section>

      <?php mildolares_paginador(); ?>

    <?php else : ?>

      <div class="text-center py-20 border border-dashed border-gray-300 rounded-xl bg-gray-50 mt-10">
        <p class="text-lg font-semibold mb-2"><?php esc_html_e('Todavía no hay artículos publicados.', 'mil'); ?></p>
        <p class="text-gray-600"><?php esc_html_e('Genera el primero desde Sistema → MIL IA.', 'mil'); ?></p>
      </div>

    <?php endif; ?>

    <?php wp_reset_postdata(); ?>

    <!-- CTA NEWSLETTER -->
    <section class="mt-16 rounded-xl bg-green-900 text-white p-8">
      <div class="flex flex-col md:flex-row md:items-center gap-6 justify-between">
        <div class="max-w-xl">
          <h2 class="text-2xl font-bold mb-2">
            <?php esc_html_e('Un resumen útil cada semana. Sin humo.', 'mil'); ?>
          </h2>
          <p class="text-green-100">
            <?php esc_html_e('Cifras, negocios y plataformas que sí mueven la aguja, resumidas en cinco minutos.', 'mil'); ?>
          </p>
        </div>
        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
          <input type="hidden" name="action" value="mil_newsletter_subscribe">
          <?php wp_nonce_field('mil_newsletter', 'mil_newsletter_nonce'); ?>
          <label class="sr-only" for="mil-newsletter-email"><?php esc_html_e('Correo electrónico', 'mil'); ?></label>
          <input id="mil-newsletter-email" type="email" name="email" required
                 placeholder="<?php esc_attr_e('tu@correo.com', 'mil'); ?>"
                 class="rounded-lg px-4 py-3 w-full sm:w-64 text-gray-900">
          <button type="submit" class="rounded-lg bg-white text-green-900 font-semibold px-6 py-3 hover:bg-green-100 transition">
            <?php esc_html_e('Suscribirme', 'mil'); ?>
          </button>
        </form>
      </div>
    </section>

  </div>

  <?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>
