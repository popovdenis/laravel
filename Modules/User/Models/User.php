<?php

namespace Modules\User\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingCreditHistory;
use Modules\Stream\Models\Stream;
use Modules\Subscription\Models\Subscription;
use Modules\Subscription\Models\SubscriptionTransaction;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles;
    use Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'prefix',
        'firstname',
        'middlename',
        'lastname',
        'suffix',
        'dob',
        'gender',
        'email_verified_at',
        'password',
        'remember_token',
        'credit_balance',
        'stripe_id',
        'pm_type',
        'pm_last_four',
        'trial_ends_at',
        'confirmation',
    ];

    protected $casts = [
        'dob'               => 'date',
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canManageBlogPosts(): bool
    {
        return $this->hasRole('Admin');
    }

    public function getNameAttribute(): string
    {
        return $this->getFullNameAttribute();
    }

    public function getFullNameAttribute(): string
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function streams()
    {
        return $this->hasMany(Stream::class, 'teacher_id');
    }

    public function scheduleTimeslots()
    {
        return $this->hasMany(\Modules\ScheduleTimeslot\Models\ScheduleTimeslot::class, 'user_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function getActiveSubscription(): ?Subscription
    {
        return $this->subscriptions()->where('stripe_status', '!=', 'canceled')->latest()->first();
    }

    public function isSubscribed($type = 'default', $price = null): bool
    {
        return $this->subscribed($type, $price);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'student_id');
    }

    public function bookingCreditHistory(): HasMany
    {
        return $this->hasMany(BookingCreditHistory::class);
    }

    public function getAvailableCredits(): int
    {
        return $this->bookingCreditHistory()->sum('credits_amount');
    }

    public function userCreditHistory(): HasMany
    {
        return $this->hasMany(SubscriptionTransaction::class);
    }

    public function creditTopUps()
    {
        return $this->hasMany(SubscriptionTransaction::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(\Modules\Order\Models\Order::class);
    }

    public function getTimesheetAttribute(): array
    {
        return $this->scheduleTimeslots->map(function ($slot) {
            return [
                'day' => $slot->day,
                'start' => $slot->start,
                'end' => $slot->end,
            ];
        })->toArray();
    }

    public function getCreditBalance(): int
    {
        return $this->credit_balance;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole('super_admin') || $this->hasRole('Admin');
    }
}
