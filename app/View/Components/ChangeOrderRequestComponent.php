<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ChangeOrderRequestComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $change_orders = [];
    public function __construct($change_orders)
    {   
        $this->change_orders = $change_orders;  
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.change-order-request-component');
    }
}
