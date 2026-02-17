<aside class="md:col-span-1">

  <div class="sticky top-12 space-y-12 text-sm">

    <!-- Buscador -->
    <div>
      <?php get_search_form(); ?>
    </div>

    <div class="flex flex-col items-center">
      <h3 class="mb-4 font-semibold text-gray-800">
        🎯 Camino a $1000 mensuales
      </h3>
      <div class="relative w-16 h-32 bg-gray-200 rounded-full overflow-hidden">
        <div
          id="thermoFill"
          class="absolute bottom-0 w-full bg-cyan-500 transition-all duration-1000"
          style="height: 1%;">
        </div>
      </div>
      <p class="mt-3 font-bold text-lg">$10.00</p>
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

