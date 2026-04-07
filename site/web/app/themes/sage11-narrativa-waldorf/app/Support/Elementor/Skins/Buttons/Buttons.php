<?php

namespace App\Support\Elementor\Skins\Buttons;

class Buttons
{
    public function __construct()
    {
        // Add button skins
        add_action('elementor/widget/button/skins_init', [
            $this,
            'registerSkins',
        ]);
        // // Add button controls
        add_action(
            'elementor/element/button/section_button/after_section_start',
            [$this, 'addDarkThemeControl'],
            10,
            2
        );
        // // Remove default button controls
        add_action(
            'elementor/element/before_section_end',
            [$this, 'removeButtonControls'],
            10,
            3
        );
    }

    public function registerSkins($button)
    {
        collect([
            [
                new ButtonType(
                    'primary',
                    'btn btn-primary',
                    __('Primary button', 'sage')
                ),
            ],
            [
                new ButtonType(
                    'secondary',
                    'btn btn-secondary',
                    __('Secondary button', 'sage')
                ),
            ],
            [
                new ButtonType(
                    'tertiary',
                    'btn btn-tertiary',
                    __('Tertiary button', 'sage')
                ),
            ],
            [new ButtonType('link', 'btn btn-link', __('Link button', 'sage'))],
        ])->each(function ($item) use ($button) {
            $button->add_skin(new BaseButtonSkin($button, ...$item));
        });
    }

    public function addDarkThemeControl($button, $args)
    {
        $button->add_control('dark_theme', [
            'label' => esc_html__('Use dark theme?', 'sage'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'show_label' => true,
        ]);
    }

    public function removeButtonControls($section, $section_id, $args)
    {
        if (
            $section->get_name() == 'button' &&
            $section_id == 'section_button'
        ) {
            $section->remove_control('button_type');
            $section->remove_control('size');
        }
    }
}
