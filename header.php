<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>

<body <?php body_class("bg-white text-gray-900"); ?>>

<header class="border-b">
  <div class="max-w-site mx-auto px-4 py-4 flex items-center justify-between">

    <!-- Logo -->
    <div class="flex items-center space-x-4">
      <?php if (has_custom_logo()) {
        the_custom_logo();
      } else { ?>
        <a href="<?php echo home_url(); ?>" class="text-2xl font-bold">
          <?php bloginfo('name'); ?>
        </a>
      <?php } ?>
    </div>

    <!-- Botón móvil -->
    <button id="menuToggle" class="md:hidden text-2xl">
      ☰
    </button>

    <!-- Menú -->
    <nav id="menu" class="hidden md:block">
      <?php
      wp_nav_menu([
          'theme_location' => 'primary',
          'container' => false,
          'menu_class' => 'flex space-x-6 font-medium'
      ]);
      ?>
    </nav>

  </div>

  <!-- Menú móvil -->
  <div id="mobileMenu" class="hidden md:hidden px-4 pb-4">
    <?php
    wp_nav_menu([
        'theme_location' => 'primary',
        'container' => false,
        'menu_class' => 'flex flex-col space-y-3'
    ]);
    ?>
  </div>
</header>

<script>
document.getElementById('menuToggle').addEventListener('click', function(){
  document.getElementById('mobileMenu').classList.toggle('hidden');
});
</script>

<main class="max-w-site mx-auto px-4 py-10">