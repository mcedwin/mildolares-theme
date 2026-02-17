<?php get_header(); ?>

<div class="max-w-site mx-auto px-6">

  <div class="grid grid-cols-1 md:grid-cols-3 gap-12">

    <!-- CONTENIDO PRINCIPAL -->
    <div class="md:col-span-2">

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
    <?php get_sidebar(); ?>

  </div>

</div>

<?php get_footer(); ?>