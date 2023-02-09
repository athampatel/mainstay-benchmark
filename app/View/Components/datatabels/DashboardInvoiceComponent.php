<?php

namespace App\View\Components\datatabels;

use Illuminate\View\Component;

class DashboardInvoiceComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $invoices = [];
    public function __construct($invoices)
    {
        $this->invoices = $invoices;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.datatabels.dashboard-invoice-component');
    }
}
