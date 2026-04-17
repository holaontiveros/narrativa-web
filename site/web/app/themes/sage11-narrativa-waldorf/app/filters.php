<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

add_filter('excerpt_length', function () {
    return 20;
}, 999);


add_filter('acf/settings/save_json', function ($path) {
    $path = get_stylesheet_directory() . '/app/acf-json';
    return $path;
});

add_filter('acf/settings/load_json', function ($paths) {
    // Remove the original path (optional).
    unset($paths[0]);

    // Append the new path and return it.
    $paths[] = get_stylesheet_directory() . '/app/acf-json';

    return $paths;
});

add_action('acf/init', function () {
    if (function_exists('acf_add_options_page')) {

        acf_add_options_page(array(
            'page_title'    => 'Configuraciones del Tema',
            'menu_title'    => 'Configuraciones del Tema',
            'menu_slug'     => 'theme-general-settings',
            'capability'    => 'edit_posts',
            'redirect'      => false
        ));
    }
});

add_filter('should_load_separate_core_block_assets', '__return_false');
