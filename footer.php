  </main>

  <footer class="mt-16 border-t border-gray-200 bg-gray-50">
    <div class="max-w-site mx-auto px-4 sm:px-6 py-12 grid grid-cols-1 md:grid-cols-3 gap-10">

      <div>
        <?php if (has_custom_logo()) {
          the_custom_logo('array', array('class' => 'h-12 w-auto'));
        } else { ?>
          <p class="text-xl font-bold"><?php bloginfo('name'); ?></p>
        <?php } ?>
        <p class="text-sm text-gray-600 mt-3 max-w-xs"><?php echo esc_html(mil_site_description()); ?></p>
      </div>

      <nav aria-label="<?php esc_attr_e('Menú del pie', 'mil'); ?>">
        <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500 mb-3">
          <?php esc_html_e('Navegación', 'mil'); ?>
        </h2>
        <?php
        wp_nav_menu([
            'theme_location' => 'footer',
            'container'      => false,
            'menu_class'     => 'flex flex-col gap-2 text-sm',
            'fallback_cb'    => '__return_empty_string',
        ]);
        ?>
        <?php if (! has_nav_menu('footer')) : ?>
          <ul class="flex flex-col gap-2 text-sm">
            <li><a class="hover:text-green-900" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Inicio', 'mil'); ?></a></li>
            <?php
            wp_list_categories(array(
                'title_li' => '',
                'orderby'  => 'count',
                'order'    => 'DESC',
                'number'   => 5,
            ));
            ?>
          </ul>
        <?php endif; ?>
      </nav>

      <div>
        <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500 mb-3">
          <?php esc_html_e('Sígueme', 'mil'); ?>
        </h2>
        <div class="flex gap-4 text-sm">
          <a class="hover:text-green-900" href="<?php echo esc_url(home_url('/rss/')); ?>"><?php esc_html_e('RSS', 'mil'); ?></a>
          <?php if (get_option('admin_email')) : ?>
            <a class="hover:text-green-900" href="<?php echo esc_url('mailto:' . get_option('admin_email')); ?>"><?php esc_html_e('Contacto', 'mil'); ?></a>
          <?php endif; ?>
        </div>
      </div>

    </div>

    <div class="border-t border-gray-200">
      <div class="max-w-site mx-auto px-4 sm:px-6 py-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-sm text-gray-500">
        <p>&copy; <?php echo esc_html(wp_date('Y')); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('Todos los derechos reservados.', 'mil'); ?></p>
        <p><?php esc_html_e('Hecho con WordPress.', 'mil'); ?></p>
      </div>
    </div>
  </footer>

  <?php wp_footer(); ?>
</body>
</html>
