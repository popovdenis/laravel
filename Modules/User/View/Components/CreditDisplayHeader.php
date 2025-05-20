<?php

namespace Modules\User\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class CreditDisplayHeader extends CreditDisplay
{
    /**
     * Get the view/contents that represent the component.
     */
    public function render(): View|string
    {
        return view('user::components.credit-display-header');
    }
}
