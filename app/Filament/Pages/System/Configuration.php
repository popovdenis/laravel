<?php
declare(strict_types=1);

namespace App\Filament\Pages\System;

use Closure;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Outerweb\FilamentSettings\Filament\Pages\Settings as BaseSettings;
use Filament\Forms\Components\Select;

/**
 * Class Configuration
 *
 * @package App\Filament\Pages\System
 */
class Configuration extends BaseSettings
{
    protected static ?string $navigationGroup = 'System';
    protected static ?string $slug = 'configuration';

    public function schema(): array|Closure
    {
        return [
            Tabs::make('Configuration')
                ->schema([
                    Tabs\Tab::make('Booking')
                        ->schema([
                            TextInput::make('booking.group_lesson_duration')
                                ->label('Duration of Group Lesson')
                                ->rules(['integer', 'min:0'])
                                ->numeric(),
                            TextInput::make('booking.individual_lesson_duration')
                                ->label('Duration of Individual Lesson')
                                ->rules(['integer', 'min:0'])
                                ->numeric(),
                            Section::make('Advanced Settings')->schema([
                                Select::make('booking.applicable_payment_method')
                                    ->options([
                                        'credits' => setting('payment.credits.title', 'Credits'),
                                        'stripe' => setting('payment.stripe.title', 'Stripe'),
                                    ])->columnSpan(6),
                            ])->columns(10)->collapsible()
                        ]),

                    Tabs\Tab::make('Subscription')
                        ->schema([
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

                    Tabs\Tab::make('Security')
                        ->schema([
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

                    Tabs\Tab::make('Sales')->schema([
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
                        ])
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
