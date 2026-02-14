<?php

function lap_assets() {
    wp_enqueue_style(
        'lap-style',
        get_template_directory_uri() . '/assets/css/style.css',
        [],
        filemtime(get_template_directory() . '/assets/css/style.css')
    );
}
add_action('wp_enqueue_scripts', 'lap_assets');

add_theme_support('title-tag');
add_theme_support('post-thumbnails');
add_theme_support('custom-logo');

register_nav_menus([
    'primary' => 'Menú Principal'
]);

add_theme_support('custom-logo', [
    'height'      => 60,
    'width'       => 200,
    'flex-height' => true,
    'flex-width'  => true,
]);

// Desactivar el editor de bloques (Gutenberg)
add_filter('use_block_editor_for_post', '__return_false', 10);

// Desactivar el editor de bloques para widgets (opcional)
add_filter('use_widgets_block_editor', '__return_false', 10);