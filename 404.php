<?php get_header(); ?>

<div class="max-w-2xl mx-auto text-center py-16">

  <p class="text-6xl font-bold text-green-800 mb-4">404</p>

  <h1 class="text-2xl md:text-3xl font-bold mb-4">
    <?php esc_html_e('¡Oops! La página no fue encontrada.', 'mil'); ?>
  </h1>

  <p class="text-gray-600 mb-10">
    <?php esc_html_e('Es posible que el enlace esté roto o que el contenido haya cambiado de dirección. Prueba con una búsqueda o vuelve al inicio.', 'mil'); ?>
  </p>

  <div class="max-w-md mx-auto mb-8">
    <?php get_search_form(); ?>
  </div>

  <div class="flex flex-wrap justify-center gap-3">
    <a href="<?php echo esc_url(home_url('/')); ?>"
       class="px-6 py-3 rounded-lg bg-green-800 text-white font-semibold hover:bg-green-900 transition">
      <?php esc_html_e('Ir al inicio', 'mil'); ?>
    </a>
    <button type="button" onclick="history.length > 1 ? history.back() : location.assign('<?php echo esc_url(home_url('/')); ?>')"
            class="px-6 py-3 rounded-lg border border-gray-300 font-semibold hover:bg-gray-100 transition">
      <?php esc_html_e('Volver atrás', 'mil'); ?>
    </button>
  </div>

</div>

<?php get_footer(); ?>
