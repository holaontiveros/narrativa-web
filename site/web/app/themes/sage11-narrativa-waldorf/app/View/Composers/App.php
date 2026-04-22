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

    // Based on the ID received return always the same image from a list of images, this is useful for the default featured image when the post doesn't have one.
    function getDefaultImage($id, $classes): string
    {
        $images = get_field('default_featured_image', 'option');

        if (!$images) {
            return '';
        }

        $index = $id % count($images);
        return wp_get_attachment_image($images[$index]['ID'], 'full', false, ['class' => $classes]);
    }

    public function getFeaturedImage($post = 0, $classes = ''): string
    {
        $post = get_post($post);
        if (has_post_thumbnail($post->ID)) {
            return get_the_post_thumbnail($post->ID, 'full', ['class' => $classes]);
        }

        $defaultImage = $this->getDefaultImage($post->ID, $classes);
        return $defaultImage;
    }

    public function getSocialLinks(): array
    {
        $social_links = get_field('networks', 'option');
        return $social_links ? $social_links : [];
    }
}
