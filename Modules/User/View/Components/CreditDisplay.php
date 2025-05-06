<?php

namespace Modules\User\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class CreditDisplay extends Component
{
    public int $credits;
    public string $size;

    public function __construct(string $size = 'base')
    {
        $this->credits = auth()->user()?->credit_balance ?? 0;
        $this->size = $size;
    }

    public function color(): string
    {
        return match (true) {
            $this->credits === 0 => 'text-red-600',
            $this->credits < 5 => 'text-yellow-500',
            default => 'text-green-600',
        };
    }

    public function textSize(): string
    {
        return match ($this->size) {
            'sm' => 'text-sm font-normal',
            'lg' => 'text-lg font-semibold',
            default => '',
        };
    }

    /**
     * Get the view/contents that represent the component.
     */
    public function render(): View|string
    {
        return view('user::components.credit-display');
    }
}
