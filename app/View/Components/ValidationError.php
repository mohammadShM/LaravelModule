<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ValidationError extends Component
{

    public $field;

    public function __construct($field)
    {
        $this->field = $field;
    }

    public function render()
    {
        return view('components.validation-error');
    }
}
