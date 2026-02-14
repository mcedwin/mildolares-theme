<?php get_header(); ?>

<div class="max-w-site mx-auto px-6">

  <div class="grid grid-cols-1 md:grid-cols-4 gap-12">

    <!-- CONTENIDO PRINCIPAL -->
    <div class="md:col-span-3">

      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <article class="leading-relaxed text-gray-900">

          <!-- Título -->
          <h1 class="text-4xl font-bold mb-10 leading-tight">
            <?php the_title(); ?>
          </h1>

          <!-- Contenido -->
          <div class="prose prose-lg max-w-3xl">
            <?php the_content(); ?>
          </div>

        </article>

      <?php endwhile; endif; ?>

    </div>

    <!-- SIDEBAR -->
    <aside class="md:col-span-1">

      <div class="sticky top-12 space-y-12 text-sm">

        <!-- Buscador -->
        <div>
          <?php get_search_form(); ?>
        </div>

        <!-- Categorías -->
        <div>
          <h3 class="font-semibold mb-4 border-b pb-2">
            Categorías
          </h3>
          <ul class="space-y-2">
            <?php wp_list_categories(['title_li' => '']); ?>
          </ul>
        </div>

        <!-- Últimos posts -->
        <div>
          <h3 class="font-semibold mb-4 border-b pb-2">
            Últimas noticias
          </h3>
          <ul class="space-y-3">
            <?php
            $recent = new WP_Query([
              'posts_per_page' => 5
            ]);
            while ($recent->have_posts()) : $recent->the_post(); ?>
              <li>
                <a href="<?php the_permalink(); ?>" class="hover:underline">
                  <?php the_title(); ?>
                </a>
              </li>
            <?php endwhile; wp_reset_postdata(); ?>
          </ul>
        </div>

      </div>

    </aside>

  </div>

</div>

<?php get_footer(); ?>