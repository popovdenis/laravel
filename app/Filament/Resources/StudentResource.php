<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
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
                        ->default(fn ($record) => $record?->subscription?->plan_id)
                        ->dehydrated(false)
                        ->required()
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
                Tables\Columns\TextColumn::make('subscription')
                    ->label('Subscription Plan')
                    ->formatStateUsing(fn ($record) => $record->subscription?->plan?->name ?? __('No Plan')),

                Tables\Columns\TextColumn::make('credit_balance')
                    ->label('Credit Balance')
            ]
        ));
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->role('student')
            ->with(['subscription.plan']);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
