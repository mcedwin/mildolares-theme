<?php get_header(); ?>

<div class="max-w-site mx-auto px-6">

  <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

    <!-- COLUMNA PRINCIPAL -->
    <div class="md:col-span-2">

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
          <!-- <div class="text-sm text-gray-500 mb-8">
            <?php the_time('F j, Y'); ?> · <?php the_author(); ?>
          </div> -->

          <!-- Imagen destacada -->
          <?php if (has_post_thumbnail()) : ?>
              <?php the_post_thumbnail('large', ['class' => 'float-right w-full md:w-1/2 mr-6 mb-4 rounded-xl']); ?>
          <?php endif; ?>

          <!-- Contenido -->
         <div class="
    prose 
    text-gray-800
    max-w-none
    prose-h2:mt-6 
    prose-h2:mb-2
    prose-ul:my-3
    prose-li:my-1
    [&>h2]:text-emerald-700
    [&>h3]:text-emerald-700
">

            <?php the_content(); ?>

          </div>

        </article>

      <?php endwhile; endif; ?>

    </div>

    <?php get_sidebar(); ?>

  </div>

</div>

<?php get_footer(); ?>