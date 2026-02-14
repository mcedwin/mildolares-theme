<?php get_header(); ?>

<div class="max-w-site mx-auto px-6">

  <div class="grid grid-cols-1 md:grid-cols-4 gap-12">

    <!-- COLUMNA PRINCIPAL -->
    <div class="md:col-span-3">

      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <article class="leading-relaxed text-gray-900">

          <!-- Categoría -->
          <div class="text-sm text-green-900 font-semibold mb-3">
            <?php the_category(', '); ?>
          </div>

          <!-- Título -->
          <h1 class="text-4xl font-bold mb-6 leading-tight">
            <?php the_title(); ?>
          </h1>

          <!-- Meta -->
          <div class="text-sm text-gray-500 mb-8">
            <?php the_time('F j, Y'); ?> · <?php the_author(); ?>
          </div>

          <!-- Imagen destacada -->
          <?php if (has_post_thumbnail()) : ?>
            <div class="mb-10">
              <?php the_post_thumbnail('large', ['class' => 'rounded-xl w-full']); ?>
            </div>
          <?php endif; ?>

          <!-- Contenido -->
          <div class="prose prose-lg max-w-3xl">

            <?php the_content(); ?>

          </div>

        </article>

      <?php endwhile; endif; ?>

    </div>

    <?php get_sidebar(); ?>

  </div>

</div>

<?php get_footer(); ?>