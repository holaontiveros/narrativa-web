<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Post Types
    |--------------------------------------------------------------------------
    |
    | Post types to be registered with Extended CPTs
    | <https://github.com/johnbillion/extended-cpts>
    |
    */

    'post_types' => [
        'ad' => [
            'menu_icon' => 'dashicons-star-filled',
            'supports' => ['title'],
            'show_in_rest' => false,
            'names' => [
                'singular' => 'Ad',
                'plural' => 'Ads',
                'slug' => 'ads',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Taxonomies
    |--------------------------------------------------------------------------
    |
    | Taxonomies to be registered with Extended CPTs library
    | <https://github.com/johnbillion/extended-cpts>
    |
    */

    'taxonomies' => [
        // 'seed_category' => [
        //     'post_types' => ['seed'],
        //     'meta_box' => 'radio',
        //     'names' => [
        //         'singular' => 'Category',
        //         'plural' => 'Categories',
        //     ],
        // ],
    ],
];
