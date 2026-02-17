<?php get_header(); ?>

<div class="max-w-site mx-auto px-6">

  <!-- Título de categoría -->
  <header class="mb-5">

    <h1 class="text-4xl font-bold mb-4">
      <?php single_cat_title(); ?>
    </h1>

    <?php if (category_description()) : ?>
      <div class="text-gray-600 text-lg">
        <?php echo category_description(); ?>
      </div>
    <?php endif; ?>

    <div class="border-t border-gray-200"></div>

  </header>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

    <!-- COLUMNA PRINCIPAL -->
    <div class="md:col-span-2">

      <?php if (have_posts()) : ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 align-center">

          <?php while (have_posts()) : the_post(); ?>

            <article>
              <?php if (has_post_thumbnail()) : ?>
                <div class="mb-2">
                  <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('thumbnail', ['class' => 'rounded-lg w-full']); ?>
                  </a>
                </div>
              <?php endif; ?>
              <h2 class="text-2xl font-semibold mb-3">
                <a href="<?php the_permalink(); ?>" class="hover:underline">
                  <?php the_title(); ?>
                </a>
              </h2>

              <!-- <div class="text-sm text-gray-500 mb-4">
                <?php the_time('F j, Y'); ?>
              </div> -->

              <div class="leading-snug text-gray-700">
                <?php the_excerpt(); ?>
              </div>


            </article>


          <?php endwhile; ?>

        </div>

        <!-- PAGINADOR -->
        <?php mildolares_paginador(); ?>

      <?php else : ?>

        <p>No hay artículos en esta categoría.</p>

      <?php endif; ?>

    </div>

    <?php get_sidebar(); ?>

  </div>

</div>

<?php get_footer(); ?>