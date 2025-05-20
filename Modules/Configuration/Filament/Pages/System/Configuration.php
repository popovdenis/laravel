<?php
declare(strict_types=1);

namespace Modules\Configuration\Filament\Pages\System;

use Closure;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Outerweb\FilamentSettings\Filament\Pages\Settings as BaseSettings;

/**
 * Class Configuration
 *
 * @package App\Filament\Pages\System
 */
class Configuration extends BaseSettings
{
    protected static ?string $navigationGroup = 'System';
    protected static ?string $slug = 'configuration';
    protected static ?int $navigationSort = 1;

    public function schema(): array|Closure
    {
        $timezones = collect(\DateTimeZone::listIdentifiers())
            ->mapWithKeys(fn($tz) => [$tz => $tz])
            ->toArray();
        return [
            Tabs::make('Configuration')->schema([
                Tabs\Tab::make('General')->schema([
                    Section::make('Country Options')->schema([
                        Select::make('base.locale.timezone')
                            ->label('Default Country')
                            ->placeholder('-- Please Select --')
                            ->options($timezones)
                            ->required(),
                    ]),
                ]),

                Tabs\Tab::make('Booking Settings')->schema([
                    Section::make('General')->schema([
                        TextInput::make('booking.rules.group_lesson_duration')
                            ->label('Duration of Group Lesson')
                            ->rules(['integer', 'min:0'])
                            ->numeric()
                            ->afterStateHydrated(fn ($component, $state) => $state ?? $component->state(config('booking.rules.group_lesson_duration')))
                            ->columnSpan(5),
                        TextInput::make('booking.rules.individual_lesson_duration')
                            ->label('Duration of Individual Lesson')
                            ->rules(['integer', 'min:0'])
                            ->numeric()
                            ->columnSpan(5)
                            ->afterStateHydrated(fn ($component, $state) => $state ?? $component->state(config('booking.rules.individual_lesson_duration'))),
                        TextInput::make('booking.rules.cancellation_deadline')
                            ->label('Allow cancellations up to (minutes before meeting starts)')
                            ->rules(['integer', 'min:0'])
                            ->helperText('After this period, cancellations are not allowed.')
                            ->numeric()
                            ->columnSpan(5)
                            ->afterStateHydrated(fn ($component, $state) => $state ?? $component->state(config('booking.rules.cancellation_deadline'))),
                        TextInput::make('booking.rules.minimum_advance_time')
                             ->label('Minimum advance time (minutes)')
                             ->rules(['integer', 'min:0'])
                             ->helperText('Minimum advance time required before booking (in minutes)')
                             ->numeric()
                            ->columnSpan(5)
                             ->afterStateHydrated(fn ($component, $state) => $state ?? $component->state(config('booking.rules.minimum_advance_time'))),
                        TextInput::make('booking.rules.maximum_group_members_capacity')
                             ->label('Maximum group size')
                             ->rules(['integer', 'min:0'])
                             ->numeric()
                             ->columnSpan(5)
                             ->afterStateHydrated(fn ($component, $state) => $state ?? $component->state(config('booking.rules.maximum_group_members_capacity'))),
                        Select::make('booking.listing.default_lesson_type')
                              ->options([
                                  'group' => 'Group',
                                  'individual' => 'Individual',
                              ])->columnSpan(5),
                    ])->columns(10)->collapsible(),
                    Section::make('Advanced Settings')->schema([
                        Select::make('booking.applicable_payment_method')
                            ->options([
                                'credits' => setting('payment.credits.title', 'Credits'),
                                'stripe' => setting('payment.stripe.title', 'Stripe'),
                            ])->columnSpan(5),
                    ])->columns(10)->collapsible()
                ]),

                Tabs\Tab::make('Subscription')->schema([
                    TextInput::make('subscription.group_lesson_price')
                        ->label('Price per Group Lesson (credits)')
                        ->rules(['integer', 'min:0'])
                        ->numeric(),
                    TextInput::make('subscription.individual_lesson_price')
                        ->label('Price per Individual Lesson (credits)')
                        ->rules(['integer', 'min:0'])
                        ->numeric(),
                    Section::make('Advanced Settings')->schema([
                        Select::make('subscription.applicable_payment_method')
                            ->options([
                                'credits' => setting('payment.credits.title', 'Credits'),
                                'stripe' => setting('payment.stripe.title', 'Stripe'),
                            ])->columnSpan(6),
                    ])->columns(10)->collapsible()
                ]),

                Tabs\Tab::make('Security')->schema([
                    TextInput::make('security.max_number_booking_requests')
                        ->label('Max Number of Booking Requests')
                        ->rules(['integer', 'min:0'])
                        ->numeric()
                        ->helperText('Limit the number of booking request per hour. Use 0 to disable.'),
                    TextInput::make('security.min_time_between_booking_requests')
                        ->label('Min Time Between Booking Requests')
                        ->rules(['integer', 'min:0'])
                        ->numeric()
                        ->helperText('Delay in seconds between booking requests. Use 0 to disable.'),
                ]),

                Tabs\Tab::make('Customers')->schema([
                    Section::make('Customer Configuration')->schema([
                        Section::make('Create New Account Options')->schema([
                            Toggle::make('customer.create_account.confirm')
                                ->label('Require Emails Confirmation')
                                ->columnSpan(6),
                        ])->columns(10)->collapsible(),

                        Section::make('Password Options')->schema([
                            TextInput::make('customer.password.required_character_classes_number')
                                ->label('Number of Required Character Classes')
                                ->helperText('Number of different character classes required in password: Lowercase, Uppercase, Digits, Special Characters.')
                                ->columnSpan(6),
                            TextInput::make('customer.password.minimum_password_length')
                                ->label('Minimum Password Length')
                                ->rules(['integer', 'min:0'])
                                ->numeric()
                                ->helperText('Please enter a number 1 or greater in this field.')
                                ->columnSpan(6),
                        ])->columns(10)->collapsible(),
                    ])
                ]),

                Tabs\Tab::make('Sales')->schema([
                    Section::make('General')->schema([
                        Toggle::make('subscription.general.reset_credits_on_plan_change')
                            ->label('Clear customer credits when switching plans')
                            ->helperText('If disabled, the credits will be added to the customer’s existing credit balance.')
                            ->columnSpan(6),
                    ])->collapsible(),
                    Section::make('Payment Methods')->schema([
                        Section::make('Credits')->schema([
                            Toggle::make('payment.credits.active')
                                ->label('Enabled')
                                ->columnSpan(6),
                            TextInput::make('payment.credits.title')
                                ->label('Title')
                                ->columnSpan(6),
                        ])->columns(10)->collapsible(),

                        Section::make('Stripe')->schema([
                            Toggle::make('payment.stripe.active')
                                ->label('Enabled')
                                ->columnSpan(6),
                            TextInput::make('payment.stripe.title')
                                ->label('Title')
                                ->columnSpan(6),
                        ])->columns(10)->collapsible(),
                    ])->collapsible()
                ]),

                Tabs\Tab::make('System')->schema([
                    Section::make('Mail Sending Settings')->schema([
                        Toggle::make('smtp.enable')
                            ->label('Enable Email Communications')
                            ->columnSpan(6),
                        Select::make('smtp.transport')
                            ->options([
                                'mail_sender' => 'MailSender',
                            ])
                            ->columnSpan(6),
                    ])->columns(10)->collapsible()
                ]),
            ]),
        ];
    }

    public static function getNavigationLabel() : string
    {
        return __('Configuration');
    }

    public function getTitle() : string
    {
        return __('Configuration');
    }
}
