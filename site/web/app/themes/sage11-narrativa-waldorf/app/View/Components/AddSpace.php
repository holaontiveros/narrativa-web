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
        // Possible ad spaces: footer, main_featured, post_start, cat_start
        // ad_spaces has [location, scope, banner, post => [], tax => [] ]
        $adSpaces = collect(get_field('ad_spaces', 'option'));
        // Get the right adSpace taking in account scope overrides
        $adSpacesForLocation = $adSpaces
            ->where('location', $this->spaceId);

        $selectedAdSpace = null;

        if(is_single()) {
            $postId = get_the_ID();
            $selectedAdSpace = $adSpacesForLocation
                ->where('scope', 'post')
                ->filter(function ($adSpace) use ($postId) {
                    return in_array($postId, (array) $adSpace['post']);
                })
                ->first();

            if (!$selectedAdSpace) {
                $categories = get_the_category($postId);
                $categoryIds = wp_list_pluck($categories, 'term_id');

                $selectedAdSpace = $adSpacesForLocation
                    ->where('scope', 'category')
                    ->filter(function ($adSpace) use ($categoryIds) {
                        return !empty(array_intersect((array) ($adSpace['tax'] ?? []), (array) $categoryIds));
                    })
                    ->first();
            }

            if (!$selectedAdSpace) {
                $selectedAdSpace = $adSpacesForLocation
                    ->where('scope', 'site')
                    ->first();
            }
        } else if (is_category()) {
            $categoryId = get_queried_object_id();
            $selectedAdSpace = $adSpacesForLocation
                ->where('scope', 'category')
                ->filter(function ($adSpace) use ($categoryId) {
                    return in_array($categoryId, (array) ($adSpace['tax'] ?? []));
                })
                ->first();

            if (!$selectedAdSpace) {
                $selectedAdSpace = $adSpacesForLocation
                    ->where('scope', 'site')
                    ->first();
            }
        } else {
            $selectedAdSpace = $adSpacesForLocation
                ->where('scope', 'site')
                ->first();
        }

        return view('components.add-space', [
            'bannerId' => $selectedAdSpace['banner'] ?? null,
        ]);
    }
}
