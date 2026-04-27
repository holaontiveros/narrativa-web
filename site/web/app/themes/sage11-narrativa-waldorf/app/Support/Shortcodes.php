<?php

namespace App\Support;

use Roots\view;
use Illuminate\Support\Facades\Vite;
use WP_Query;

/**
 * Shortcodes
 */
class Shortcodes
{
    private $cache_key_pre = 'shortcode_';
    private $cache_enabled = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        if ('WP_ENV' == 'development') {
            $this->cache_enabled = false;
        }

        $shortcodes = [
            'brand',
            'featured-posts',
            'category-posts',
        ];

        collect($shortcodes)->map(function ($shortcode) {
            return add_shortcode($shortcode, [$this, strtr($shortcode, ['-' => '_'])]);
        });
    }

    /**
     * Compiles blade template for shortcode
     *
     * @param  string  $path Path to blade template from views/ folder
     * @param  array $data Data for blade to pass to the view
     * @param  string $cache_key Use carefully, don't optimize without measuring
     * @return string
     */
    private function get_template_content($path, $data = [], $cache_key = '')
    {
        if (!$path) {
            return __('No shortcode path provided', 'sage');
        }

        $use_cache = $cache_key && $this->cache_enabled;
        $content = '';

        if ($use_cache) {
            $key = $this->cache_key_pre . $cache_key;
            $content = get_transient($key);
        }

        if (!$content) {
            $content = view($path, $data)->render();

            if ($use_cache) {
                set_transient($key, $content, 24 * HOUR_IN_SECONDS);
            }
        }

        return $content;
    }

    /**
     * [brand]
     * Prints brand logo
     *
     * @param  array  $atts
     * @param  string $content
     * @return string
     */
    public function brand($atts, $content = null)
    {
        $logo_path = isset($atts['type']) ? Vite::asset('resources/images/logo-' . esc_html($atts['type']) . '.svg') : Vite::asset('resources/images/logo.svg');
        $logo_mobile = isset($atts['type']) ? Vite::asset('resources/images/logo-' . esc_html($atts['type']) . '.svg') : Vite::asset('resources/images/logo.svg');

        $link = isset($atts['link']) ? esc_url($atts['link']) : null;

        $sizeClasess = ['large' => 'h-26', 'small' => 'h-14'];
        $sizeClass = isset($atts['size']) && isset($sizeClasess[$atts['size']]) ? $sizeClasess[$atts['size']] : $sizeClasess['small'];

        return $this->get_template_content('shortcodes/brand', [
            'logo_path' => $logo_path,
            'mobile_logo_path' => $logo_mobile,
            'size' => $sizeClass,
            'link' => $link,
        ]);
    }

    /**
     * [featured-posts]
     * Prints featured posts
     *
     * @param  array  $atts
     * @param  string $content
     * @return string
     */
    public function featured_posts($atts, $content = null)
    {
        // gets ramdom posts with "featured" tag, you can change it to your needs


        $mexicoCategory = get_category_by_slug('mexico');
        $internationalCategory = get_category_by_slug('internacional');

        $main_post = get_posts([
            'tag' => 'featured',
            'numberposts' => 1,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'category' => $mexicoCategory ? $mexicoCategory->term_id : null,
        ]);

        if (empty($main_post)) {
            $main_post = get_posts([
                'numberposts' => 1,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'category' => $mexicoCategory ? $mexicoCategory->term_id : null,
            ]);
        }
        $main_post = $main_post ? $main_post[0] : null;

        $totalPostRightSection = 2;

        $posts = get_posts([
            'tag' => 'featured',
            'numberposts' => $totalPostRightSection,
            'post__not_in' => $main_post ? [$main_post->ID] : [],
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);

        if(count($posts) != $totalPostRightSection) {
            $posts = array_merge($posts, get_posts([
                'numberposts' => $totalPostRightSection - count($posts),
                'post__not_in' => $main_post ? array_merge([$main_post->ID], wp_list_pluck($posts, 'ID')) : wp_list_pluck($posts, 'ID'),
                'orderby'        => 'date',
                'order'          => 'DESC',
                'category' => $internationalCategory ? $internationalCategory->term_id : null,
            ]));
        }

        return $this->get_template_content('shortcodes/featured-posts', ['posts' => $posts, 'mainPost' => $main_post]);
    }


    /**
     * [category-posts category="news"]
     * Prints posts from category
     *
     * @param  array  $atts
     * @param  string $content
     * @return string
     */
    public function category_posts($atts, $content = null)
    {
        $title = isset($atts['title']) ? esc_html($atts['title']) : null;
        $category = isset($atts['category']) ? esc_html($atts['category']) : null;
        $amount = isset($atts['amount']) ? intval($atts['amount']) : 3;
        $post_query = new WP_Query([
            'category_name' => $category,
            'posts_per_page' => $amount,
        ]);
        return $this->get_template_content('shortcodes/category-posts', ['query' => $post_query, 'main_title' => $title]);
    }
}
