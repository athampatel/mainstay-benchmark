<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PaginationComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $pagination = "";
    public $search = "";
    public function __construct($pagination = [],$search = "")
    {
        $this->pagination = $pagination;
        $this->search = $search;
    }

    // public function index() {
    //     $exampleData = 'example data';
    //     return view('example', compact('exampleData'));
    // }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.pagination-component');
    }
}
