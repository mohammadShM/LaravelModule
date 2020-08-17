<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Input extends Component
{

    public $type;
    public $name;
    public $placeholder;

    public function __construct($type, $name, $placeholder)
    {
        $this->type = $type;
        $this->name = $name;
        $this->placeholder = $placeholder;
    }

    public function render()
    {
        return view('components.input');
    }
}
