<?php get_header(); ?>

<div class="max-w-site mx-auto px-6">

  <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
    <!-- COLUMNA PRINCIPAL -->
    <div class="md:col-span-2">
      <?php
      $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

      $args = [
        'post_type' => 'post',
        'posts_per_page' => 7,
        'paged' => $paged
      ];

      $query = new WP_Query($args);
      ?>

      <?php if ($query->have_posts()) : ?>

        <?php $query->the_post(); ?>

        <!-- NOTICIA PORTADA -->
        <!-- Título ancho completo -->
    <h2 class="text-4xl font-bold mb-6 leading-tight">
        <a href="<?php the_permalink(); ?>" class="hover:underline">
            <?php the_title(); ?>
        </a>
    </h2>

    <!-- Imagen + contenido -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">

        <!-- Imagen -->
        <div>
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('large', ['class' => 'rounded-xl w-full']); ?>
            <?php endif; ?>
        </div>

        <!-- Contenido -->
        <div class="text-lg text-gray-800 leading-relaxed">

            <div class="text-sm text-gray-500 mb-4">
                <?php the_time('F j, Y'); ?>
            </div>

            <?php the_excerpt(); ?>

            <div class="mt-6">
                <a href="<?php the_permalink(); ?>" 
                   class="text-green-900 font-semibold hover:underline">
                   Leer más →
                </a>
            </div>

        </div>

    </div>
<div class="border-t border-gray-300 mt-20 mb-16"></div>
        <!-- GRID DE NOTICIAS -->
        <section class="grid md:grid-cols-3 gap-8">

          <?php while ($query->have_posts()) : $query->the_post(); ?>

            <article>
              <a href="<?php the_permalink(); ?>">

                <?php if (has_post_thumbnail()) : ?>
                  <div class="mb-4">
                    <?php the_post_thumbnail('medium', ['class' => 'rounded-lg w-full']); ?>
                  </div>
                <?php endif; ?>

                <h3 class="text-xl font-semibold mb-2 hover:underline">
                  <?php the_title(); ?>
                </h3>

              </a>

              <div class="text-sm text-gray-500">
                <?php the_time('F j, Y'); ?>
              </div>

            </article>

          <?php endwhile; ?>

        </section>

        <!-- PAGINADOR -->
        <div class="mt-16">
          <?php
          echo paginate_links([
            'total' => $query->max_num_pages,
            'current' => $paged,
            'prev_text' => '←',
            'next_text' => '→'
          ]);
          ?>
        </div>
    </div>

    <?php get_sidebar(); ?>
  </div>

</div>
<?php endif;
      wp_reset_postdata(); ?>

<?php get_footer(); ?>