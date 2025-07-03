<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SelectLimitDropdown extends Component
{
    public $name;
    public $options;
    public $selected;
    public $attributes;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $options = [], $selected = null)
    {
        $this->name = $name;
        $this->options = $options;
        $this->selected = $selected;
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.select-limit-dropdown');
    }
}
