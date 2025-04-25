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
                    Tabs\Tab::make('Study')
                        ->schema([
                            TextInput::make('study.group_lesson_duration')
                                ->label('Duration of Group Lesson'),
                            TextInput::make('study.individual_lesson_duration')
                                ->label('Duration of Individual Lesson'),
                        ]),

                    Tabs\Tab::make('Subscription')
                        ->schema([
                            TextInput::make('study.group_lesson_price')
                                ->label('Price per Group Lesson (credits)'),
                            TextInput::make('study.individual_lesson_price')
                                ->label('Price per Individual Lesson (credits)'),
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
