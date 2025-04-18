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
                    Tabs\Tab::make('General')
                        ->schema([
                            TextInput::make('general.brand_name')
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
