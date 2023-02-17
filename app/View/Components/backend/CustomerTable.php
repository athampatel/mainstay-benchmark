<?php

namespace App\View\Components\backend;

use Illuminate\View\Component;

class CustomerTable extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $users;
    public function __construct($users)
    {
        $this->$users = $users;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.backend.customer-table');
    }
}
