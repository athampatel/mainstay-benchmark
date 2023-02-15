<?php

namespace App\View\Components\datatabels;

use Illuminate\View\Component;

class AnalysisPageComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $analysisdata = [];
    public function __construct($analysisdata)
    {
        $this->analysisdata = $analysisdata;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.datatabels.analysis-page-component');
    }
}
