<?php

namespace App\Support\Elementor\Skins\Buttons;

use Elementor\Widget_Base;
use Elementor\Icons_Manager;

trait ButtonTrait
{
    protected $button_type;

    /**
     * Render button widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @param \Elementor\Widget_Base|null $instance
     *
     * @since  3.4.0
     * @access protected
     */
    protected function render_button(Widget_Base $instance = null)
    {
        if (empty($instance)) {
            $instance = $this;
        }

        $settings = $instance->get_settings_for_display();
        // do_action('qm/debug', $settings);

        $instance->add_render_attribute(
            'wrapper',
            'class',
            'elementor-button-wrapper'
        );
        $instance->add_render_attribute(
            'wrapper',
            'class',
            $settings['dark_theme'] ? 'dark' : ''
        );

        if (!empty($settings['link']['url'])) {
            $instance->add_link_attributes('button', $settings['link']);
            $instance->add_render_attribute(
                'button',
                'class',
                'elementor-button-link'
            );
        }

        $instance->add_render_attribute(
            'button',
            'class',
            'elementor-button ' . $this->button_type->class
        );

        $instance->add_render_attribute('button', 'role', 'button');

        if (!empty($settings['button_css_id'])) {
            $instance->add_render_attribute(
                'button',
                'id',
                $settings['button_css_id']
            );
        }

        if (!empty($settings['size'])) {
            $instance->add_render_attribute(
                'button',
                'class',
                'elementor-size-' . $settings['size']
            );
        }

        if (!empty($settings['hover_animation'])) {
            $instance->add_render_attribute(
                'button',
                'class',
                'elementor-animation-' . $settings['hover_animation']
            );
        }
        ?>
        <div <?php $instance->print_render_attribute_string('wrapper'); ?>>
            <a <?php $instance->print_render_attribute_string('button'); ?>>
                <?php $this->render_text($instance); ?>
            </a>
        </div>
    <?php
    }

    /**
     * Render button text.
     *
     * Render button widget text.
     *
     * @param \Elementor\Widget_Base|null $instance
     *
     * @since  3.4.0
     * @access protected
     */
    protected function render_text(Widget_Base $instance = null)
    {
        // The default instance should be `$this` (a Button widget), unless the Trait is being used from outside of a widget (e.g. `Skin_Base`) which should pass an `$instance`.
        if (empty($instance)) {
            $instance = $this;
        }

        $settings = $instance->get_settings_for_display();

        $migrated = isset($settings['__fa4_migrated']['selected_icon']);
        $is_new =
            empty($settings['icon']) && Icons_Manager::is_migration_allowed();

        $instance->add_render_attribute( [
			'content-wrapper' => [
				'class' => 'elementor-button-content-wrapper',
			],
			'icon' => [
				'class' => 'elementor-button-icon',
			],
			'text' => [
				'class' => 'elementor-button-text',
			],
		] );
        // TODO: replace the protected with public
        //$instance->add_inline_editing_attributes( 'text', 'none' );
        ?>
        <span <?php $instance->print_render_attribute_string(
            'content-wrapper'
        ); ?>>
            <?php if (
                !empty($settings['icon']) ||
                !empty($settings['selected_icon']['value'])
            ): ?>
                <span <?php $instance->print_render_attribute_string(
                    'icon-align'
                ); ?>>
                    <?php if ($is_new || $migrated):
                        Icons_Manager::render_icon($settings['selected_icon'], [
                            'aria-hidden' => 'true',
                        ]);
                    else:
                         ?>
                        <i class="<?php echo esc_attr(
                            $settings['icon']
                        ); ?>" aria-hidden="true"></i>
                    <?php
                    endif; ?>
                </span>
            <?php endif; ?>
            <span <?php $instance->print_render_attribute_string(
                'text'
            ); ?>><?php echo $settings['text']; ?></span>
        </span>
<?php
    }
}
