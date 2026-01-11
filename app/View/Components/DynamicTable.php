<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DynamicTable extends Component
{
    public $columns;
    public $title;
    public $table;
    public $addButtons;


    /**
     * Create a new component instance.
     */
    public function __construct($columns, $title, $addButtons, $table)
    {
        $this->columns = $columns;
        $this->title = $title;
        $this->addButtons = $addButtons;
        $this->table = $table;

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dynamic-table');
    }
}
