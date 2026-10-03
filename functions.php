<?php

function lap_assets() {
    wp_enqueue_style(
        'lap-style',
        get_template_directory_uri() . '/assets/css/style.css',
        [],
        filemtime(get_template_directory() . '/assets/css/style.css')
    );

    wp_enqueue_script(
        'lap-nav',
        get_template_directory_uri() . '/assets/js/nav.js',
        [],
        filemtime(get_template_directory() . '/assets/js/nav.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'lap_assets');

add_theme_support('title-tag');
add_theme_support('post-thumbnails');
add_theme_support('custom-logo', [
    'height'      => 60,
    'width'       => 200,
    'flex-height' => true,
    'flex-width'  => true,
]);
add_theme_support('automatic-feed-links');
add_theme_support('responsive-embeds');
add_theme_support('align-wide');

register_nav_menus([
    'primary'   => 'Menú Principal',
    'footer'    => 'Menú del Pie',
    'legal'     => 'Enlaces legales',
]);

// Desactivar el editor de bloques (Gutenberg)
add_filter('use_block_editor_for_post', '__return_false', 10);

// Desactivar el editor de bloques para widgets (opcional)
add_filter('use_widgets_block_editor', '__return_false', 10);

// Tamaños por defecto
add_action('after_switch_theme', function () {
    update_option('thumbnail_size_w', 254);
    update_option('thumbnail_size_h', 174);
    update_option('thumbnail_crop', 1);

    update_option('medium_size_w', 360);
    update_option('medium_size_h', 272);
    update_option('medium_crop', 1);

    update_option('large_size_w', 1000);
    update_option('large_size_h', 0);
});


function mildolares_paginador($query = null) {

    if (!$query) {
        global $wp_query;
        $query = $wp_query;
    }

    if (! $query instanceof WP_Query) {
        return;
    }

    $total_pages  = (int) $query->max_num_pages;
    $current_page = max(1, (int) (get_query_var('paged') ? get_query_var('paged') : get_query_var('page')));

    if ($total_pages <= 1) return;

    $window = 2;
    $start  = max(1, $current_page - $window);
    $end    = min($total_pages, $current_page + $window);

    ?>
    <div class="mt-16 flex justify-center">
        <nav aria-label="<?php esc_attr_e('Paginación', 'mil'); ?>">
            <ul class="flex flex-wrap items-center justify-center gap-2 text-sm">

                <?php if ($current_page > 1) : ?>
                    <li>
                        <a href="<?php echo esc_url(get_pagenum_link($current_page - 1)); ?>"
                           rel="prev"
                           class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-green-800 hover:text-white transition">
                            <?php esc_html_e('←', 'mil'); ?>
                            <span class="sr-only"><?php esc_html_e('Anterior', 'mil'); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($start > 1) : ?>
                    <li>
                        <a href="<?php echo esc_url(get_pagenum_link(1)); ?>"
                           class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-green-800 hover:text-white transition">1</a>
                    </li>
                    <?php if ($start > 2) : ?>
                        <li class="px-2 text-gray-400">…</li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php for ($i = $start; $i <= $end; $i++) : ?>
                    <?php if ($i == $current_page) : ?>
                        <li>
                            <span aria-current="page"
                                  class="px-4 py-2 bg-green-800 text-white rounded-lg border border-green-800"><?php echo (int) $i; ?></span>
                        </li>
                    <?php else : ?>
                        <li>
                            <a href="<?php echo esc_url(get_pagenum_link($i)); ?>"
                               class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-green-800 hover:text-white transition"><?php echo (int) $i; ?></a>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($end < $total_pages) : ?>
                    <?php if ($end < $total_pages - 1) : ?>
                        <li class="px-2 text-gray-400">…</li>
                    <?php endif; ?>
                    <li>
                        <a href="<?php echo esc_url(get_pagenum_link($total_pages)); ?>"
                           class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-green-800 hover:text-white transition"><?php echo (int) $total_pages; ?></a>
                    </li>
                <?php endif; ?>

                <?php if ($current_page < $total_pages) : ?>
                    <li>
                        <a href="<?php echo esc_url(get_pagenum_link($current_page + 1)); ?>"
                           rel="next"
                           class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-green-800 hover:text-white transition">
                            <?php esc_html_e('→', 'mil'); ?>
                            <span class="sr-only"><?php esc_html_e('Siguiente', 'mil'); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

            </ul>
        </nav>
    </div>
    <?php
}


// ---------------------------------------------------------------------------
// Helpers de contenido
// ---------------------------------------------------------------------------

function mil_reading_time($post = null) {
    $post    = get_post($post);
    $content = $post ? $post->post_content : '';
    $words   = str_word_count(wp_strip_all_tags(strip_shortcodes($content)));

    return max(1, (int) ceil($words / 200));
}

function mil_thumbnail_alt($post_id = null) {
    $post_id = $post_id ? $post_id : get_the_ID();
    $alt     = get_post_meta($post_id, '_wp_attachment_image_alt', true);

    if ($alt) {
        return $alt;
    }

    $thumb_id = get_post_thumbnail_id($post_id);

    if ($thumb_id) {
        $thumb_alt = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);

        if ($thumb_alt) {
            return $thumb_alt;
        }

        $attachment = get_post($thumb_id);

        if ($attachment && $attachment->post_excerpt) {
            return $attachment->post_excerpt;
        }
    }

    return get_the_title($post_id);
}

function mil_post_terms($post_id, $limit = 2) {
    $terms = get_the_terms($post_id, 'category');

    if (! $terms || is_wp_error($terms)) {
        return array();
    }

    return array_slice($terms, 0, $limit);
}

function mil_first_term_link($post_id) {
    $terms = mil_post_terms($post_id, 1);

    if (! $terms) {
        return '';
    }

    $link = get_category_link($terms[0]->term_id);

    return $link ? $link : '';
}

function mil_date_line() {
    printf(
        '<time datetime="%s">%s</time>',
        esc_attr(get_the_date('c')),
        esc_html(get_the_date())
    );
}

function mil_site_description() {
    $desc = trim(wp_strip_all_tags(get_bloginfo('description')));

    return $desc ? $desc : __('Análisis, datos y estrategias reales sobre Inversiones, Negocios y Plataformas.', 'mil');
}


// ---------------------------------------------------------------------------
// Meta description, Open Graph, Twitter y schema.org
// ---------------------------------------------------------------------------

function mil_has_seo_plugin() {
    return defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION') || defined('SEOPRESS_VERSION');
}

function mil_meta_description() {
    if (mil_has_seo_plugin()) {
        return;
    }

    $description = '';

    if (is_singular()) {
        $description = mil_ai_meta_or_excerpt();
    } elseif (is_category() || is_tag() || is_tax()) {
        $description = wp_strip_all_tags(term_description());
    } elseif (is_author()) {
        $description = wp_strip_all_tags(get_the_author_meta('description'));
    } elseif (is_front_page()) {
        $description = mil_site_description();
    } elseif (is_home()) {
        $description = mil_site_description();
    }

    if (! $description) {
        $description = mil_site_description();
    }

    $description = trim(preg_replace('/\s+/', ' ', wp_strip_all_tags($description)));

    printf(
        "<meta name=\"description\" content=\"%s\" />\n",
        esc_attr(wp_html_excerpt($description, 300, ''))
    );
}

function mil_ai_meta_or_excerpt() {
    $meta = get_post_meta(get_the_ID(), '_mil_ai_meta_description', true);

    if ($meta) {
        return $meta;
    }

    $excerpt = get_the_excerpt();

    return $excerpt ? $excerpt : get_the_content();
}

function mil_og_tags() {
    if (mil_has_seo_plugin()) {
        return;
    }

    if (is_front_page() || is_home()) {
        $title       = get_bloginfo('name') . ' — ' . mil_site_description();
        $url         = home_url('/');
        $description = mil_site_description();
        $image       = '';
        $type        = 'website';
    } elseif (is_singular()) {
        $title       = wp_get_document_title();
        $url         = get_permalink();
        $description = mil_ai_meta_or_excerpt();
        $image       = get_the_post_thumbnail_url(get_the_ID(), 'large');
        $type        = 'article';
    } elseif (is_category() || is_tag() || is_tax()) {
        $title       = single_term_title('', false);
        $url         = get_term_link(get_queried_object());
        $description = wp_strip_all_tags(term_description());
        $image       = '';
        $type        = 'website';
    } else {
        return;
    }

    $description = trim(preg_replace('/\s+/', ' ', wp_strip_all_tags((string) $description)));
    $description = wp_html_excerpt($description, 200, '');

    printf("<meta property=\"og:site_name\" content=\"%s\" />\n", esc_attr(get_bloginfo('name')));
    printf("<meta property=\"og:title\" content=\"%s\" />\n", esc_attr($title));
    printf("<meta property=\"og:type\" content=\"%s\" />\n", esc_attr($type));
    printf("<meta property=\"og:url\" content=\"%s\" />\n", esc_url($url));
    printf("<meta property=\"og:description\" content=\"%s\" />\n", esc_attr($description));
    printf("<meta property=\"og:locale\" content=\"%s\" />\n", esc_attr(str_replace('-', '_', get_bloginfo('language'))));

    if ($image) {
        printf("<meta property=\"og:image\" content=\"%s\" />\n", esc_url($image));
        printf("<meta name=\"twitter:card\" content=\"summary_large_image\" />\n");
    } else {
        printf("<meta name=\"twitter:card\" content=\"summary\" />\n");
    }

    printf("<meta name=\"twitter:title\" content=\"%s\" />\n", esc_attr($title));
    printf("<meta name=\"twitter:description\" content=\"%s\" />\n", esc_attr($description));

    if (is_singular()) {
        printf("<meta property=\"article:published_time\" content=\"%s\" />\n", esc_attr(get_the_date('c')));
        printf("<meta property=\"article:modified_time\" content=\"%s\" />\n", esc_attr(get_the_modified_date('c')));
    }
}

function mil_schema_json() {
    if (mil_has_seo_plugin()) {
        return;
    }

    $graph = array();

    $site = array(
        '@type' => 'WebSite',
        '@id'   => home_url('/#website'),
        'url'   => home_url('/'),
        'name'  => get_bloginfo('name'),
    );

    $organization = array(
        '@type' => 'Organization',
        '@id'   => home_url('/#organization'),
        'url'   => home_url('/'),
        'name'  => get_bloginfo('name'),
    );

    if (has_custom_logo()) {
        $logo_id = get_theme_mod('custom_logo');
        $logo    = wp_get_attachment_image_src($logo_id, 'full');

        if ($logo) {
            $organization['logo'] = array(
                '@type' => 'ImageObject',
                'url'   => $logo[0],
            );
        }
    }

    $site['publisher'] = array('@id' => home_url('/#organization'));

    if (is_singular('post')) {
        $image = get_the_post_thumbnail_url(get_the_ID(), 'large');

        $article = array(
            '@type'            => 'BlogPosting',
            '@id'              => get_permalink() . '#article',
            'isPartOf'         => array('@id' => home_url('/#website')),
            'mainEntityOfPage' => get_permalink(),
            'headline'         => wp_strip_all_tags(get_the_title()),
            'description'      => wp_html_excerpt(trim(preg_replace('/\s+/', ' ', wp_strip_all_tags(mil_ai_meta_or_excerpt()))), 300, ''),
            'datePublished'    => get_the_date('c'),
            'dateModified'     => get_the_modified_date('c'),
            'author'           => array(
                '@type' => 'Person',
                'name'  => get_the_author(),
            ),
            'publisher'        => array('@id' => home_url('/#organization')),
            'wordCount'        => str_word_count(wp_strip_all_tags(get_the_content())),
            'inLanguage'       => get_bloginfo('language'),
        );

        if ($image) {
            $article['image'] = array($image);
        }

        $terms = get_the_terms(get_the_ID(), 'category');

        if ($terms && ! is_wp_error($terms)) {
            $article['articleSection'] = wp_list_pluck($terms, 'name');
        }

        $graph[] = $article;
    }

    $graph[] = $organization;
    $graph[] = $site;

    $json = wp_json_encode(
        array(
            '@context' => 'https://schema.org',
            '@graph'   => $graph,
        ),
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    );

    if ($json) {
        printf("<script type=\"application/ld+json\">%s</script>\n", $json);
    }
}

add_action('wp_head', 'mil_meta_description', 2);
add_action('wp_head', 'mil_og_tags', 3);
add_action('wp_head', 'mil_schema_json', 20);


// ---------------------------------------------------------------------------
// Contador de vistas
// ---------------------------------------------------------------------------

function mil_record_view() {
    if (is_admin() || ! is_singular('post')) {
        return;
    }

    $post_id = get_queried_object_id();

    if (! $post_id) {
        return;
    }

    $visitor = md5(isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'local');
    $key     = 'mil_views_' . $post_id;
    $seen    = get_transient($key);

    $seen = is_array($seen) ? $seen : array();

    if (isset($seen[$visitor])) {
        return;
    }

    $seen[$visitor] = time();

    if (count($seen) > 400) {
        $seen = array_slice($seen, -200, null, true);
    }

    set_transient($key, $seen, DAY_IN_SECONDS);

    $count = (int) get_post_meta($post_id, 'post_views_count', true);

    update_post_meta($post_id, 'post_views_count', $count + 1);
}
add_action('wp_head', 'mil_record_view', 1);

function mil_views_of($post_id = null) {
    $post_id = $post_id ? $post_id : get_the_ID();

    return (int) get_post_meta($post_id, 'post_views_count', true);
}

function mil_popular_posts($limit = 5, $fallback_days = 60) {
    $query = new WP_Query(array(
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => $limit,
        'meta_key'            => 'post_views_count',
        'orderby'             => 'meta_value_num',
        'order'               => 'DESC',
        'ignore_sticky_posts' => true,
        'no_found_rows'       => true,
    ));

    if ($query->have_posts()) {
        return $query;
    }

    return new WP_Query(array(
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => $limit,
        'ignore_sticky_posts' => true,
        'no_found_rows'       => true,
    ));
}


// ---------------------------------------------------------------------------
// Ajustes del panel lateral (Camino a $1000, tipo de cambio)
// ---------------------------------------------------------------------------

function mil_goal_settings() {
    $defaults = array(
        'goal'        => 1000,
        'current'     => 10,
        'label'       => __('Camino a $1000 USD/mes', 'mil'),
        'description' => '',
        'stages'      => array(
            array('Fundamentos',   'Generación de contenido y diseño',           0,   5),
            array('Validación',    'Primer tráfico y primeros ingresos',        5,  15),
            array('Optimización',  'Afiliados y mejora de RPM',                15,  40),
            array('Escalamiento',  'Producto digital y captación de leads',    40,  70),
            array('Meta $1000',    'Monetización consolidada',                 70, 101),
        ),
    );

    $saved = get_option('mil_goal_settings', array());

    return wp_parse_args(is_array($saved) ? $saved : array(), $defaults);
}

function mil_stage_state($min, $max, $percent) {
    if ($percent >= $max) {
        return 'completado';
    }

    if ($percent >= $min) {
        return 'actual';
    }

    return 'pendiente';
}

function mil_goal_percent($goal, $current) {
    $goal = (float) $goal;

    if ($goal <= 0) {
        return 0;
    }

    return max(0, min(100, ((float) $current / $goal) * 100));
}

function mil_exchange_rate() {
    $rate = get_option('mil_exchange_rate', array());

    if (empty($rate['buy']) && empty($rate['sell'])) {
        return array();
    }

    return $rate;
}

function mil_number($value, $decimals = 2) {
    return number_format_i18n((float) $value, $decimals);
}


// ---------------------------------------------------------------------------
// Suscriptores del boletin
// ---------------------------------------------------------------------------

function mil_register_subscriber_cpt() {
    register_post_type('mil_suscriptor', array(
        'labels'          => array(
            'name'          => __('Suscriptores', 'mil'),
            'singular_name' => __('Suscriptor', 'mil'),
            'menu_name'      => __('Suscriptores', 'mil'),
            'all_items'     => __('Todos', 'mil'),
            'search_items'  => __('Buscar', 'mil'),
            'not_found'     => __('Sin suscriptores', 'mil'),
        ),
        'public'          => false,
        'show_ui'         => true,
        'show_in_menu'    => true,
        'menu_icon'       => 'dashicons-email-alt',
        'menu_position'   => 4,
        'supports'        => array('title'),
        'capability_type' => 'post',
    ));
}
add_action('init', 'mil_register_subscriber_cpt');

function mil_subscribe() {
    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $ok    = $email && is_email($email);

    if ($ok) {
        $existing = get_posts(array(
            'post_type'      => 'mil_suscriptor',
            'post_status'    => 'any',
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'title'          => $email,
        ));

        if ($existing) {
            wp_update_post(array(
                'ID'          => $existing[0],
                'post_status' => 'publish',
            ));
        } else {
            wp_insert_post(array(
                'post_type'   => 'mil_suscriptor',
                'post_status' => 'publish',
                'post_title'  => $email,
            ));
        }
    }

    wp_safe_redirect(add_query_arg('mil_newsletter', $ok ? 'ok' : 'error', wp_get_referer() ? wp_get_referer() : home_url('/')));
    exit;
}
add_action('admin_post_nopriv_mil_newsletter_subscribe', 'mil_subscribe');
add_action('admin_post_mil_newsletter_subscribe', 'mil_subscribe');

function mil_newsletter_notice() {
    $state = isset($_GET['mil_newsletter']) ? sanitize_key(wp_unslash($_GET['mil_newsletter'])) : '';

    if (! $state) {
        return;
    }

    $is_ok = 'ok' === $state;

    printf(
        '<div class="%s fixed bottom-4 right-4 z-50 px-5 py-4 rounded-lg shadow-lg text-sm font-medium">%s</div>',
        $is_ok ? 'bg-green-900 text-white' : 'bg-red-700 text-white',
        $is_ok ? esc_html__('Listo. Ya estás suscrito.', 'mil') : esc_html__('Introduce un correo válido.', 'mil')
    );
}
add_action('wp_footer', 'mil_newsletter_notice', 5);


// ---------------------------------------------------------------------------
// Tabla de contenido automática
// ---------------------------------------------------------------------------

function mil_toc($content) {
    if (false === stripos($content, '<h2')) {
        return '';
    }

    preg_match_all('#<h2\b[^>]*>(.*?)</h2>#is', $content, $matches);

    if (count($matches[1]) < 3) {
        return '';
    }

    $items = '';

    foreach ($matches[1] as $index => $heading) {
        $text  = trim(wp_strip_all_tags($heading));
        $id    = 'seccion-' . ($index + 1);
        $items .= sprintf(
            '<li><a href="#%1$s" class="block py-1 border-l-2 border-transparent hover:border-green-800 hover:text-green-900 hover:pl-3 transition">%2$s</a></li>',
            esc_attr($id),
            esc_html($text)
        );
    }

    return '<nav class="border border-gray-200 rounded-xl p-5 bg-gray-50 mb-8" aria-label="' . esc_attr__('Índice del artículo', 'mil') . '">' .
           '<h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500 mb-3">' . esc_html__('En este artículo', 'mil') . '</h2>' .
           '<ol class="text-sm space-y-1">' . $items . '</ol>' .
           '</nav>';
}

function mil_add_heading_ids($content) {
    if (false === stripos($content, '<h2')) {
        return $content;
    }

    $index = 0;

    return preg_replace_callback(
        '#<h2\b([^>]*)>(.*?)</h2>#is',
        function ($matches) use (&$index) {
            $index++;
            $attrs = $matches[1];

            if (preg_match('/\bid\s*=/i', $attrs)) {
                return $matches[0];
            }

            return '<h2 id="seccion-' . $index . '"' . $attrs . '>' . $matches[2] . '</h2>';
        },
        $content
    );
}
add_filter('the_content', 'mil_add_heading_ids', 20);


// ---------------------------------------------------------------------------
// Modulo MIL IA (redaccion asistida con OpenAI)
// ---------------------------------------------------------------------------

define('MIL_AI_VERSION', '1.1.0');

require_once get_template_directory() . '/sys/ai/Env.php';
require_once get_template_directory() . '/sys/ai/OpenAI.php';
require_once get_template_directory() . '/sys/ai/Prompt.php';
require_once get_template_directory() . '/sys/ai/Generator.php';
require_once get_template_directory() . '/sys/ai/Admin.php';

MIL_AI_Admin::instance();
