<?php

namespace Modules\Theme\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CometChatWidget extends Component
{
    public function __construct(
        public string $groupId
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.comet-chat-widget');
    }
}
