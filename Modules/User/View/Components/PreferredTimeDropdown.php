<?php
declare(strict_types=1);

namespace Modules\User\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Class PreferredTimeDropdown
 *
 * @package Modules\User\View\Components
 */
class PreferredTimeDropdown extends Component
{
    public string $startTime;
    public string $endTime;

    public function __construct()
    {
        $this->startTime = auth()->user()->getAttribute('preferred_start_time');
        $this->endTime = auth()->user()->getAttribute('preferred_end_time');
    }

    /**
     * Get the view/contents that represent the component.
     */
    public function render(): View|string
    {
        return view('user::components.preferred-time-dropdown');
    }
}