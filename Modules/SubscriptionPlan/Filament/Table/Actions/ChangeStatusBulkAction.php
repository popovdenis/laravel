<?php
declare(strict_types=1);

namespace Modules\SubscriptionPlan\Filament\Table\Actions;

use Filament\Tables\Actions\BulkAction;

/**
 * Class ChangeStatusBulkAction
 *
 * @package Modules\SubscriptionPlan\Filament\Table\Actions
 */
class ChangeStatusBulkAction extends BulkAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Toggle Status')
            ->icon('heroicon-o-arrows-right-left')
            ->action(function ($records) {
                foreach ($records as $record) {
                    $record->status = !$record->status;
                    $record->save();
                }
            })
            ->color('warning')
            ->requiresConfirmation();
    }
}
