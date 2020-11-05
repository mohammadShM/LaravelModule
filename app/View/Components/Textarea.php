<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Textarea extends Component
{

    public $name;
    public $value;
    public $placeholder;

    public function __construct($placeholder, $name, $value = null)
    {

        $this->name = $name;
        $this->value = $value;
        $this->placeholder = $placeholder;
    }

    public function render()
    {
        return view('components.textarea');
    }
}
