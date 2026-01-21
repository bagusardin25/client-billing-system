<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AdminLayout extends Component
{
    public string $title;
    public string $active;
    public array $breadcrumbs;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $title = 'Dashboard',
        string $active = '',
        array $breadcrumbs = []
    ) {
        $this->title = $title;
        $this->active = $active;
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('layouts.admin');
    }
}
