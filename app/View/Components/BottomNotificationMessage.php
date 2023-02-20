<?php

namespace App\View\Components;

use Illuminate\View\Component;

class BottomNotificationMessage extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public string $title,
        public string $desc,
        public string $icon,
        public string $time,
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.bottom-notification-message');
    }
}
