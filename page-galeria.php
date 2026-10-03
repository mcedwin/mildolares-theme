<?php
/*
Template Name: Page Galeria
*/
get_header();
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-10">

  <div class="md:col-span-2">

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

      <article>

        <header class="mb-8 pb-4 border-b border-gray-200">
          <h1 class="text-3xl md:text-4xl font-bold leading-tight">
            <?php the_title(); ?>
          </h1>
        </header>

        <?php if (trim(get_the_content())) : ?>
          <div class="prose prose-lg max-w-none text-gray-800 mb-8">
            <?php the_content(); ?>
          </div>
        <?php endif; ?>

        <?php
        $mil_galeria = get_post_gallery(get_the_ID(), false);

        if (is_array($mil_galeria) && ! empty($mil_galeria['ids'])) :
            $mil_ids = array_filter(array_map('absint', explode(',', $mil_galeria['ids'])));
            ?>

          <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php foreach ($mil_ids as $mil_id) : ?>
              <?php
              $mil_full = wp_get_attachment_image_src($mil_id, 'large');

              if (! $mil_full) {
                  continue;
              }
              ?>
              <a href="<?php echo esc_url($mil_full[0]); ?>"
                 class="block group"
                 data-fancybox="galeria"
                 rel="galeria_img">
                <?php
                echo wp_get_attachment_image($mil_id, 'thumbnail', false, [
                    'class'   => 'rounded-lg w-full object-cover group-hover:opacity-90 transition',
                    'alt'     => get_post_meta($mil_id, '_wp_attachment_image_alt', true),
                    'loading' => 'lazy',
                ]);
                ?>
              </a>
            <?php endforeach; ?>
          </div>

        <?php else : ?>

          <div class="text-center py-12 border border-dashed border-gray-300 rounded-xl bg-gray-50">
            <p class="text-gray-600"><?php esc_html_e('Esta página no tiene una galería de imágenes asignada.', 'mil'); ?></p>
          </div>

        <?php endif; ?>

      </article>

    <?php endwhile; endif; ?>

  </div>

  <?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>
