<?php

namespace Modules\Subscription\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Subscription\Models\Subscription;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;
    protected static ?string $navigationGroup = 'Subscriptions';
    protected static ?string $navigationLabel = 'Subscribers';
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(12)->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Subscriber')
                    ->options(\Modules\User\Models\User::pluck('firstname', 'id'))
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
                    ->label('Subscription Plan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('stripe_status')->badge()
                    ->label('Subscription Status')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'canceled',
//                        'danger' => StreamStatus::FINISHED,
                    ]),

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
            'index' => SubscriptionResource\Pages\ListSubscriptions::route('/'),
            'edit' => SubscriptionResource\Pages\EditSubscription::route('/{record}/edit'),
            'view' => SubscriptionResource\Pages\ViewSubscription::route('/{record}/view'),
        ];
    }
}
