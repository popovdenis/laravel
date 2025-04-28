<?php
declare(strict_types=1);

namespace App\Filament\Pages\System;

use Closure;
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

    public function schema(): array|Closure
    {
        return [
            Tabs::make('Configuration')
                ->schema([
                    Tabs\Tab::make('Booking')
                        ->schema([
                            TextInput::make('booking.group_lesson_duration')
                                ->label('Duration of Group Lesson')
                                ->rules(['required', 'integer', 'min:0'])
                                ->numeric(),
                            TextInput::make('booking.individual_lesson_duration')
                                ->label('Duration of Individual Lesson')
                                ->rules(['required', 'integer', 'min:0'])
                                ->numeric(),
                        ]),

                    Tabs\Tab::make('Subscription')
                        ->schema([
                            TextInput::make('subscription.group_lesson_price')
                                ->label('Price per Group Lesson (credits)')
                                ->rules(['required', 'integer', 'min:0'])
                                ->numeric(),
                            TextInput::make('subscription.individual_lesson_price')
                                ->label('Price per Individual Lesson (credits)')
                                ->rules(['required', 'integer', 'min:0'])
                                ->numeric(),
                        ]),

                    Tabs\Tab::make('Security')
                        ->schema([
                            TextInput::make('security.min_time_between_booking_requests')
                                ->label('Min Time Between Booking Requests')
                                ->rules(['required', 'integer', 'min:0'])
                                ->numeric()
                                ->helperText('Delay in minutes between booking requests. Use 0 to disable.'),
                        ]),

                    Tabs\Tab::make('MailSender')
                        ->schema([
                            Toggle::make('mailsender.use_mail_sender')
                                ->label('Use MailSender')
                                ->required(),
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
