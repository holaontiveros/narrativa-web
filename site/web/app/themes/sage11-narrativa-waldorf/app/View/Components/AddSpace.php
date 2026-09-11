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

        $matchingAds = collect();

        if (is_single()) {
            $postId = get_the_ID();
            $postMatches = $ads
                ->where('scope', 'post')
                ->filter(function ($ad) use ($postId) {
                    return in_array($postId, $ad['posts']);
                });

            if ($postMatches->isNotEmpty()) {
                $matchingAds = $postMatches;
            } else {
                $categoryIds = wp_list_pluck(get_the_category($postId), 'term_id');

                $categoryMatches = $ads
                    ->where('scope', 'category')
                    ->filter(function ($ad) use ($categoryIds) {
                        return !empty(array_intersect($ad['tax'], (array) $categoryIds));
                    });

                $matchingAds = $categoryMatches->isNotEmpty()
                    ? $categoryMatches
                    : $ads->where('scope', 'site');
            }
        } else if (is_category()) {
            $categoryId = get_queried_object_id();
            $categoryMatches = $ads
                ->where('scope', 'category')
                ->filter(function ($ad) use ($categoryId) {
                    return in_array($categoryId, $ad['tax']);
                });

            $matchingAds = $categoryMatches->isNotEmpty()
                ? $categoryMatches
                : $ads->where('scope', 'site');
        } else {
            $matchingAds = $ads->where('scope', 'site');
        }

        $adSpaceOverride = $_GET['ad_space'] ?? null;
        $adSpaceIndex = is_string($adSpaceOverride)
            ? filter_var(wp_unslash($adSpaceOverride), FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1],
            ])
            : false;

        if ($adSpaceIndex !== false) {
            // Preview a specific creative from every Ad in the winning
            // targeting tier. Sorting makes a preview URL deterministic.
            $selectedCreative = $matchingAds
                ->sortBy('id')
                ->flatMap(function ($ad) {
                    return collect($ad['ad_spaces'])->map(function ($adSpace) use ($ad) {
                        return [
                            'ad_type' => $ad['ad_type'],
                            'content' => $adSpace,
                        ];
                    });
                })
                ->values()
                ->get($adSpaceIndex - 1);

            $adType = $selectedCreative['ad_type'] ?? 'image';
            $selectedAdSpace = $selectedCreative['content'] ?? null;
        } else {
            // Preserve the existing production rotation: choose an Ad first,
            // then independently choose one of its creative rows.
            $selectedAd = $matchingAds->isNotEmpty() ? $matchingAds->random() : null;
            $adType = $selectedAd['ad_type'] ?? 'image';
            $adSpaces = collect($selectedAd['ad_spaces'] ?? []);
            $selectedAdSpace = $adSpaces->isNotEmpty() ? $adSpaces->random() : null;
        }

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
