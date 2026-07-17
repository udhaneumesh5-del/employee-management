<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    public $type;
    public $class;
    public $text;
    public $icon;

    public function __construct($type = 'submit', $class = 'btn-primary', $text = 'Submit', $icon = '')
    {
        $this->type = $type;
        $this->class = $class;
        $this->text = $text;
        $this->icon = $icon;
    }

    public function render()
    {
        return view('components.button');
    }
}