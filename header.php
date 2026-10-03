<a href="#contenido" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:top-2 focus:left-2 focus:px-4 focus:py-2 focus:bg-green-800 focus:text-white focus:rounded-lg">
  <?php esc_html_e('Saltar al contenido', 'mil'); ?>
</a>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>

<body <?php body_class('bg-white text-gray-900'); ?>>
<?php wp_body_open(); ?>

<header class="border-b">
  <div class="max-w-site mx-auto px-4 sm:px-6 py-4 flex items-center justify-between gap-4">

    <!-- Logo -->
    <div class="flex items-center shrink-0">
      <?php if (has_custom_logo()) {
        the_custom_logo();
      } else { ?>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="text-2xl font-bold">
          <?php bloginfo('name'); ?>
        </a>
      <?php } ?>
    </div>

    <!-- Menú -->
    <nav id="menu" class="hidden md:block flex-1" aria-label="<?php esc_attr_e('Menú principal', 'mil'); ?>">
      <?php
      wp_nav_menu([
          'theme_location' => 'primary',
          'container'      => false,
          'menu_class'     => 'flex flex-wrap items-center gap-x-6 gap-y-2 font-medium',
          'fallback_cb'    => '__return_empty_string',
      ]);
      ?>
    </nav>

    <!-- Buscador -->
    <div class="hidden md:block shrink-0">
      <?php get_search_form(); ?>
    </div>

    <!-- Botón móvil -->
    <button id="menuToggle"
            type="button"
            class="md:hidden text-2xl leading-none px-2 py-1"
            aria-expanded="false"
            aria-controls="mobileMenu">
      <span class="sr-only"><?php esc_html_e('Abrir menú', 'mil'); ?></span>
      <span aria-hidden="true">☰</span>
    </button>

  </div>

  <!-- Buscador móvil -->
  <div class="md:hidden max-w-site mx-auto px-4 sm:px-6 pb-3">
    <?php get_search_form(); ?>
  </div>

  <!-- Menú móvil -->
  <div id="mobileMenu" class="hidden md:hidden max-w-site mx-auto px-4 sm:px-6 pb-4 border-t border-gray-100">
    <nav aria-label="<?php esc_attr_e('Menú móvil', 'mil'); ?>">
      <?php
      wp_nav_menu([
          'theme_location' => 'primary',
          'container'      => false,
          'menu_class'     => 'flex flex-col gap-3 py-3 font-medium',
          'fallback_cb'    => '__return_empty_string',
      ]);
      ?>
    </nav>
  </div>
</header>

<main id="contenido" class="max-w-site mx-auto px-4 sm:px-6 py-10">
