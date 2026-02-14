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

    <!-- Últimas noticias -->
    <div>
      <h3 class="font-semibold mb-4 border-b pb-2">
        Últimas noticias
      </h3>
      <ul class="space-y-3">
        <?php
        $recent = new WP_Query([
          'posts_per_page' => 5,
          'post__not_in' => [get_the_ID()]
        ]);
        while ($recent->have_posts()) : $recent->the_post(); ?>
          <li>
            <a href="<?php the_permalink(); ?>" class="hover:underline">
              <?php the_title(); ?>
            </a>
          </li>
        <?php endwhile;
        wp_reset_postdata(); ?>
      </ul>
    </div>

  </div>

</aside>