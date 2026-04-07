<?php

namespace App\Support\Elementor\Skins\Buttons;

use Elementor;

/**
 * Adding our Skin Class on Elementor Init
 */
class BaseButtonSkin extends Elementor\Skin_Base
{
    use ButtonTrait;

    public function __construct(Elementor\Widget_Base $parent, ButtonType $type)
    {
        parent::__construct($parent);

        // Initialize button type
        $this->button_type = $type;

        // Add template to show changes in live editor
        add_filter(
            'elementor/widget/print_template',
            [$this, 'skin_print_template'],
            10,
            2
        );
    }

    public function get_id()
    {
        return $this->button_type->id;
    }

    public function get_title()
    {
        return $this->button_type->nice_name;
    }

    public function render()
    {
        $this->render_button($this->parent);
    }

    public function skin_print_template($content, $button)
    {
        if ('button' == $button->get_name()) {
            // If we don't want a JavaScript Template, just uncomment this below
            return '';

            // ob_start();
            // $this->_content_template();
            // $content = ob_get_clean();
        }
        return $content;
    }
}
