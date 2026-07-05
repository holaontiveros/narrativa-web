<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AddSpace extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $spaceId,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        // Possible scopes: site, category, post
        // Possible ad locations: footer, main_featured, post_start, cat_start
        // Each `ad` CPT post has: ad_type, scope, post (rel), tax, location[], ad_spaces (repeater).
        $adPosts = get_posts([
            'post_type' => 'ad',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'no_found_rows' => true,
        ]);

        $ads = collect($adPosts)
            ->map(function ($post) {
                return [
                    'id' => $post->ID,
                    'ad_type' => get_field('ad_type', $post->ID),
                    'scope' => get_field('scope', $post->ID),
                    'posts' => (array) (get_field('post', $post->ID) ?: []),
                    'tax' => (array) (get_field('tax', $post->ID) ?: []),
                    'location' => (array) (get_field('location', $post->ID) ?: []),
                    'ad_spaces' => (array) (get_field('ad_spaces', $post->ID) ?: []),
                ];
            })
            ->filter(function ($ad) {
                return in_array($this->spaceId, $ad['location']);
            });

        $selectedAd = null;

        if (is_single()) {
            $postId = get_the_ID();
            $postMatches = $ads
                ->where('scope', 'post')
                ->filter(function ($ad) use ($postId) {
                    return in_array($postId, $ad['posts']);
                });
            $selectedAd = $postMatches->isNotEmpty() ? $postMatches->random() : null;

            if (!$selectedAd) {
                $categoryIds = wp_list_pluck(get_the_category($postId), 'term_id');

                $categoryMatches = $ads
                    ->where('scope', 'category')
                    ->filter(function ($ad) use ($categoryIds) {
                        return !empty(array_intersect($ad['tax'], (array) $categoryIds));
                    });
                $selectedAd = $categoryMatches->isNotEmpty() ? $categoryMatches->random() : null;
            }

            if (!$selectedAd) {
                $siteMatches = $ads->where('scope', 'site');
                $selectedAd = $siteMatches->isNotEmpty() ? $siteMatches->random() : null;
            }
        } else if (is_category()) {
            $categoryId = get_queried_object_id();
            $categoryMatches = $ads
                ->where('scope', 'category')
                ->filter(function ($ad) use ($categoryId) {
                    return in_array($categoryId, $ad['tax']);
                });
            $selectedAd = $categoryMatches->isNotEmpty() ? $categoryMatches->random() : null;

            if (!$selectedAd) {
                $siteMatches = $ads->where('scope', 'site');
                $selectedAd = $siteMatches->isNotEmpty() ? $siteMatches->random() : null;
            }
        } else {
            $siteMatches = $ads->where('scope', 'site');
            $selectedAd = $siteMatches->isNotEmpty() ? $siteMatches->random() : null;
        }

        $adType = $selectedAd['ad_type'] ?? 'image';
        $adSpaces = collect($selectedAd['ad_spaces'] ?? []);
        $selectedAdSpace = $adSpaces->isNotEmpty() ? $adSpaces->random() : null;

        return view('components.add-space', [
            'adType' => $adType,
            'sticky' => $this->spaceId === 'sticky',
            'popup' => $this->spaceId === 'popup',
            'bannerIdDesktop' => $selectedAdSpace['banner'] ?? null,
            'bannerIdMobile' => $selectedAdSpace['banner_mobile'] ?? null,
            'htmlBody' => $selectedAdSpace['html_body'] ?? null,
        ]);
    }
}
