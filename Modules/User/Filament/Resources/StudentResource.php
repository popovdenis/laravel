<?php

namespace Modules\User\Filament\Resources;

use Modules\User\Filament\Resources\StudentResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;
use Modules\User\Models\User;

class StudentResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Members';
    protected static ?string $breadcrumb = 'Members';
    protected static ?string $navigationLabel = 'Students';
    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return UserResource::form($form)->schema(array_merge(
            UserResource::form($form)->getComponents(),
            [
                Forms\Components\Grid::make(12)->schema([
                    Select::make('subscription_plan_id')
                        ->label('Subscription Plan')
                        ->options(SubscriptionPlan::pluck('name', 'id'))
                        ->required()
                        ->dehydrated(false)
                        ->afterStateHydrated(fn ($component, $state, $record) => $component->state(
                            $record?->getActiveSubscription()?->plan?->id
                        ))
                        ->columnSpan(6),

                    Forms\Components\TextInput::make('credit_balance')
                        ->label('Credit Balance')
                        ->dehydrated(false)
                        ->helperText('The current number of available credits for this student.')
                        ->numeric()
                        ->columnSpan(6)
                ]),
            ]
        ));
    }

    public static function table(Table $table): Table
    {
        return UserResource::table($table)->columns(array_merge(
            UserResource::table($table)->getColumns(),
            [
                Tables\Columns\TextColumn::make('subscriptions')
                    ->label('Subscription Plan')
                    ->formatStateUsing(fn ($record) => $record->getActiveSubscription()?->plan?->name ?? __('No Plan')),

                Tables\Columns\TextColumn::make('credit_balance')
                    ->label('Credit Balance')
            ]
        ));
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->role('student')
            /*->with(['subscription.plan'])*/;
    }

    public static function getPages(): array
    {
        return [
            'index' => StudentResource\Pages\ListStudents::route('/'),
            'create' => StudentResource\Pages\CreateStudent::route('/create'),
            'edit' => StudentResource\Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
