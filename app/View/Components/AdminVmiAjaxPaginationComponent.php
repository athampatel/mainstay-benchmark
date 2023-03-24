<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AdminVmiAjaxPaginationComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $pagination = "";
    public function __construct($pagination = [])
    {
        $this->pagination = $pagination;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin-vmi-ajax-pagination-component');
    }
}
