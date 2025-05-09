<?php
declare(strict_types=1);

namespace Modules\Base\Services;

use Modules\Base\Stdlib\DateTime\Timezone;
use Modules\User\Models\User;

/**
 * Class CustomerTimezoneFactory
 *
 * @package Modules\Base\Factories
 */
class CustomerTimezone extends Timezone
{
    protected ?User $user = null;

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getConfigTimezone($customerTimezoneId = null)
    {
        if (! $this->user && auth()->check()) {
            $this->setUser(auth()->user());
        }

        return $this->user instanceof User && $this->user->timeZoneId
            ? $this->user->timeZoneId
            : parent::getConfigTimezone($customerTimezoneId);
    }
}
