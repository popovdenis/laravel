<?php

namespace Modules\Theme\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PageBuilder extends Component
{
    public array $blocks;

    public function __construct(array $blocks = [])
    {
        $this->blocks = $blocks ?? [];
    }

    public function render(): View|Closure|string
    {
        return view('components.page-builder');
    }
}
