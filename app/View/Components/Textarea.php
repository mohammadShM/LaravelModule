<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Textarea extends Component
{

    public $placeholder;
    public $name;

    public function __construct($placeholder , $name)
    {

        $this->placeholder = $placeholder;
        $this->name = $name;
    }

    public function render()
    {
        return view('components.textarea');
    }
}
