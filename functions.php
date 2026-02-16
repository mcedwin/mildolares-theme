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

function mildolares_paginador($query = null) {

    if (!$query) {
        global $wp_query;
        $query = $wp_query;
    }

    $total_pages  = $query->max_num_pages;
    $current_page = max(1, get_query_var('paged'));

    if ($total_pages <= 1) return;

    ?>
    <div class="mt-16 flex justify-center">
        <nav aria-label="Paginación">
            <ul class="flex items-center gap-2 text-sm">

                <!-- Anterior -->
                <?php if ($current_page > 1) : ?>
                    <li>
                        <a href="<?php echo get_pagenum_link($current_page - 1); ?>"
                           class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-green-800 hover:text-white transition">
                            ←
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Números -->
                <?php for ($i = 1; $i <= $total_pages; $i++) : ?>

                    <?php if ($i == $current_page) : ?>
                        <li>
                            <span class="px-4 py-2 bg-green-800 text-white rounded-lg border border-green-800">
                                <?php echo $i; ?>
                            </span>
                        </li>
                    <?php else : ?>
                        <li>
                            <a href="<?php echo get_pagenum_link($i); ?>"
                               class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-green-800 hover:text-white transition">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endif; ?>

                <?php endfor; ?>

                <!-- Siguiente -->
                <?php if ($current_page < $total_pages) : ?>
                    <li>
                        <a href="<?php echo get_pagenum_link($current_page + 1); ?>"
                           class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-green-800 hover:text-white transition">
                            →
                        </a>
                    </li>
                <?php endif; ?>

            </ul>
        </nav>
    </div>
    <?php
}