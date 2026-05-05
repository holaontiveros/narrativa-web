<?php

/**
 * Theme setup.
 */

namespace App;

use Illuminate\Support\Facades\Vite;

/**
 * Inject styles into the block editor.
 *
 * @return array
 */
add_filter('block_editor_settings_all', function ($settings) {
    $style = Vite::asset('resources/css/editor.css');

    $settings['styles'][] = [
        'css' => "@import url('{$style}')",
    ];

    return $settings;
});

/**
 * Inject scripts into the block editor.
 *
 * @return void
 */
add_action('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) {
        return;
    }

    if (! Vite::isRunningHot()) {
        $dependencies = json_decode(Vite::content('editor.deps.json'));

        foreach ($dependencies as $dependency) {
            if (! wp_script_is($dependency)) {
                wp_enqueue_script($dependency);
            }
        }
    }
    echo Vite::withEntryPoints([
        'resources/js/editor.js',
    ])->toHtml();
});

/**
 * Use the generated theme.json file.
 *
 * @return string
 */
add_filter('theme_file_path', function ($path, $file) {
    return $file === 'theme.json'
        ? public_path('build/assets/theme.json')
        : $path;
}, 10, 2);

/**
 * Disable on-demand block asset loading.
 *
 * @link https://core.trac.wordpress.org/ticket/61965
 */
add_filter('should_load_separate_core_block_assets', '__return_false');

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
        'category_navigation' => __('Category Navigation', 'sage'),
        'footer_navigation' => __('Footer Navigation', 'sage'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');



    load_textdomain('sage', get_template_directory() . '/resources/lang/' . determine_locale() . '.mo');
}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
// add_action('widgets_init', function () {
//     $config = [
//         'before_widget' => '<section class="widget %1$s %2$s">',
//         'after_widget' => '</section>',
//         'before_title' => '<h3>',
//         'after_title' => '</h3>',
//     ];

//     register_sidebar([
//         'name' => __('Primary', 'sage'),
//         'id' => 'sidebar-primary',
//     ] + $config);

//     register_sidebar([
//         'name' => __('Footer', 'sage'),
//         'id' => 'sidebar-footer',
//     ] + $config);
// });

add_action('enqueue_block_editor_assets', function () {
    wp_dequeue_script('feedzy-gutenberg-block-js');
}, 20);





/**
 * Remove WordPress block styles completely.
 */
add_action('wp_enqueue_scripts', function () {
    // Remove all WordPress block styles
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-blocks-style'); // WooCommerce blocks
    wp_dequeue_style('classic-theme-styles');
    wp_dequeue_style('global-styles');
}, 100);

/**
 * Remove block styles from admin/editor as well.
 */
add_action('admin_enqueue_scripts', function () {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
});

/**
 * Disable WordPress global styles (theme.json output).
 */
add_filter('wp_theme_json_get_style_nodes', '__return_empty_array');

/**
 * Remove global styles inline CSS completely.
 */
remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
remove_action('wp_footer', 'wp_enqueue_global_styles', 1);

/**
 * Plugin Name: Senior Editor Role
 * Description: Adds a Senior Editor role with all Editor caps + restricted user management.
 * Version: 1.0
 */

// ─── 1. Register the role on activation ──────────────────────────────────────

function senior_editor_activate() {
    // Start from Editor's capabilities as a baseline
    $editor = get_role( 'editor' );
    $caps   = $editor ? $editor->capabilities : [];

    // Add user-management caps (we'll gate *which* users in hooks below)
    $caps['create_users'] = true;
    $caps['edit_users']   = true;
    $caps['delete_users'] = true;
    $caps['list_users']   = true;
    $caps['promote_users'] = true; // needed for role dropdowns; filtered below

    add_role( 'senior_editor', 'Senior Editor', $caps );
}

add_action( 'init', function () {
    // Ensure the role exists (in case it was added after activation)
    if ( ! get_role( 'senior_editor' ) ) {
        senior_editor_activate();
    }
 } );


// ─── 3. Helper: roles a Senior Editor may manage ─────────────────────────────

function se_manageable_roles(): array {
    // Any role at "editor" level or below (excludes senior_editor itself and admin)
    return [ 'editor', 'author', 'contributor', 'subscriber' ];
}

function se_is_senior_editor( \WP_User $user = null ): bool {
    $user = $user ?? wp_get_current_user();
    return in_array( 'senior_editor', (array) $user->roles, true );
}


// ─── 4. Filter the available roles in the user-edit role dropdown ─────────────
//    Prevents a Senior Editor from assigning senior_editor / administrator.

add_filter( 'editable_roles', function ( array $roles ): array {
    if ( ! se_is_senior_editor() ) {
        return $roles;
    }
    $allowed = se_manageable_roles();
    return array_filter( $roles, fn( $slug ) => in_array( $slug, $allowed, true ), ARRAY_FILTER_USE_KEY );
} );


// ─── 5. Block editing users who are at senior_editor level or above ───────────

add_filter( 'user_has_cap', function ( array $allcaps, array $caps, array $args, \WP_User $user ): array {
    // Only intercept for Senior Editors
    if ( ! se_is_senior_editor( $user ) ) {
        return $allcaps;
    }

    $user_management_caps = [ 'edit_user', 'delete_user', 'promote_user' ];
    if ( ! in_array( $args[0], $user_management_caps, true ) ) {
        return $allcaps;
    }

    // $args[2] is the target user ID
    $target_id = $args[2] ?? 0;
    if ( ! $target_id || $target_id === $user->ID ) {
        return $allcaps; // editing own profile is always fine
    }

    $target      = get_userdata( $target_id );
    $target_role = $target ? (array) $target->roles : [];
    $allowed     = se_manageable_roles();

    // If the target has ANY role outside the allowed list → deny
    $has_elevated = (bool) array_diff( $target_role, $allowed );
    if ( $has_elevated ) {
        $allcaps[ $args[0] ] = false;
        // Also zero out the primitive caps that were mapped
        foreach ( $caps as $cap ) {
            $allcaps[ $cap ] = false;
        }
    }

    return $allcaps;
}, 10, 4 );


// ─── 6. Restrict the Users list table to show only manageable users ───────────

add_action( 'pre_get_users', function ( \WP_User_Query $query ): void {
    if ( ! se_is_senior_editor() ) {
        return;
    }
    // Limit results to allowed roles so Senior Editors can't even *see* admins
    $query->set( 'role__in', se_manageable_roles() );
} );


// ─── 7. Block new-user creation with a disallowed role via POST ───────────────

add_action( 'user_register', function ( int $user_id ): void {
    if ( ! se_is_senior_editor() ) {
        return;
    }
    $role = $_POST['role'] ?? ''; // phpcs:ignore WordPress.Security.NonceVerification
    if ( $role && ! in_array( $role, se_manageable_roles(), true ) ) {
        // Downgrade to subscriber if someone bypassed the dropdown filter
        $user = new \WP_User( $user_id );
        $user->set_role( 'subscriber' );
    }
} );
