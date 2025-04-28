<?php
declare(strict_types=1);

namespace Modules\Security\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Security\Contracts\AttemptRequestEventInterface;

/**
 * Class PasswordResetRequestEvent
 *
 * @package Modules\Security\Models
 */
class AttemptRequestEvent extends Model implements AttemptRequestEventInterface
{
    /**
     * Customer request on a booking
     */
    const BOOKING_ATTEMPT_REQUEST = 0;

    /**
     * Customer request a password reset
     */
    const CUSTOMER_PASSWORD_RESET_REQUEST = 1;

    /**
     * Admin User request a password reset
     */
    const ADMIN_PASSWORD_RESET_REQUEST = 2;

    protected $fillable = ['request_type', 'account_reference'];

    public function setRequestType(int $requestType): self
    {
        $this->request_type = $requestType;

        return $this;
    }

    public function getRequestType(): int
    {
        return $this->request_type;
    }

    public function setAccountReference(string $accountReference): self
    {
        $this->account_reference = $accountReference;

        return $this;
    }

    public function getAccountReference(): string
    {
        return $this->account_reference;
    }

    /**
     * @param int         $securityEventType
     * @param string|null $accountReference
     *
     * @return self|null
     */
    public static function getLastRecord(int $securityEventType, string $accountReference = null): ?self
    {
        return static::where('request_type', $securityEventType)
            ->where('account_reference', $accountReference)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Delete records which has been created earlier than specified timestamp
     *
     * @param int $timestamp
     *
     * @return int
     * @throws \Exception
     */
    public function deleteRecordsOlderThan(int $timestamp): int
    {
        return static::where('created_at', '<', $timestamp)->delete();
    }
}
