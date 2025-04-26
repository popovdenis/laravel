<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionResource\Pages;
use App\Filament\Resources\SubscriptionResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Subscription\Models\Subscription;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;
    protected static ?string $navigationGroup = 'Sales';
    protected static ?string $navigationLabel = 'Subscriptions';
    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(12)->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Subscriber')
                    ->options(\Modules\User\Models\User::pluck('name', 'id'))
                    ->required()
                    ->disabled()
                    ->columnSpan(8),

                Forms\Components\Select::make('plan_id')
                    ->label('Subscription Plan')
                    ->options(\Modules\SubscriptionPlan\Models\SubscriptionPlan::pluck('name', 'id'))
                    ->required()
                    ->columnSpan(8),

                Forms\Components\Toggle::make('canceled_immediately')
                    ->label('Cancel Immediately')
                    ->columnSpan(8),

                Forms\Components\DateTimePicker::make('trial_ends_at')
                    ->label('Trial Ends At')
                    ->seconds(false)->native(false)
                    ->disabled(fn ($get) => !\Modules\SubscriptionPlan\Models\SubscriptionPlan::find($get('plan_id'))?->enable_trial)
                    ->helperText(fn ($get) =>
                    !\Modules\SubscriptionPlan\Models\SubscriptionPlan::find($get('plan_id'))?->enable_trial
                        ? 'Trial is not available for the selected plan.'
                        : null
                    )
                    ->afterStateHydrated(function ($set, $get) {
                        $plan = \Modules\SubscriptionPlan\Models\SubscriptionPlan::find($get('plan_id'));
                        if (!$plan?->enable_trial) {
                            $set('trial_ends_at', null);
                        }
                    })->columnSpan(8),

                Forms\Components\DateTimePicker::make('starts_at')->label('Plan Starts')
                    ->seconds(false)->native(false)->columnSpan(8),

                Forms\Components\DateTimePicker::make('ends_at')->label('Plan Ends')
                    ->seconds(false)->native(false)->columnSpan(8),

                Forms\Components\DateTimePicker::make('canceled_at')->label('Canceled At')
                    ->seconds(false)->native(false)->columnSpan(8),
            ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Subscriber')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('plan.name')
                    ->label('Plan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(function ($record) {
                        if ($record->canceled_at) {
                            return 'Canceled';
                        }

                        if ($record->ends_at && $record->ends_at->isPast()) {
                            return 'Expired';
                        }

                        return 'Active';
                    })
                    ->badge()
                    ->color(function ($record) {
                        if ($record->canceled_at || ($record->ends_at && $record->ends_at->isPast())) {
                            return 'danger';
                        }
                        return 'success';
                    }),

                Tables\Columns\TextColumn::make('starts_at')
                    ->label('Starts At')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('ends_at')
                    ->label('Ends At')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('trial_ends_at')
                    ->label('Trial Ends At')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('canceled_immediately')
                    ->label('Cancel Immediately')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([]); // No Create New button
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptions::route('/'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
            'view' => Pages\ViewSubscription::route('/{record}/view'),
        ];
    }
}
