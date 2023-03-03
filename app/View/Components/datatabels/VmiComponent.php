<?php

namespace App\View\Components\Datatabels;

use Illuminate\View\Component;

class VmiComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $vmiProducts = [];
    public function __construct($vmiProducts)
    {
        $this->vmiProducts = $vmiProducts;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.datatabels.vmi-component');
    }
}
