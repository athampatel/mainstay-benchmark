<?php

namespace App\View\Components\datatabels;

use Illuminate\View\Component;

class OpenOrdersPageComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $saleorders = [];
    public $is_change_order = false;
    public function __construct($saleorders,$is_change_order)
    {
        $this->saleorders = $saleorders;
        $this->is_change_order = $is_change_order;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.datatabels.open-orders-page-component');
    }
}
