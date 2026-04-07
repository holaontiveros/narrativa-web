<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class App extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        '*',
    ];

    /**
     * Retrieve the site name.
     */
    public function siteName(): string
    {
        return get_bloginfo('name', 'display');
    }

    public function getFeaturedImage($post = 0, $classes = ''): string
    {
        $post = get_post($post);
        if (has_post_thumbnail($post->ID)) {
            return get_the_post_thumbnail($post->ID, 'full', ['class' => $classes]);
        }

        return wp_get_attachment_image(get_field('default_featured_image', 'option'), 'full', false, ['class' => $classes]);
    }
}
