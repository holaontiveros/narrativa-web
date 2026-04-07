<?php

namespace App\Support\Elementor\Skins\Buttons;

class ButtonType
{
    public string $id;
    public string $class;
    public string $nice_name;

    public function __construct($id, $class, $nice_name)
    {
        $this->id = $id;
        $this->class = $class;
        $this->nice_name = $nice_name;
    }
}
