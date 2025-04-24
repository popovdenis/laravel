<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionPlanResource\Pages;
use App\Filament\Resources\SubscriptionPlanResource\RelationManagers;
use App\Models\SubscriptionPlan;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SubscriptionPlanResource extends Resource
{
    protected static ?string $model = SubscriptionPlan::class;
    protected static ?string $navigationGroup = 'Sales';
    protected static ?string $navigationLabel = 'Subscription Plans';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-date-range';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(12)->schema([
                TextInput::make('name')
                    ->label('Subscription Plan Name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(8)
                    ->helperText('Name your plan to easily identify it among other plans.
                     Your customers won\'t see it. Only you will see this plan name.'),

                Toggle::make('status')
                    ->label('Status')
                    ->columnSpanFull(),

                Forms\Components\RichEditor::make('description')
                    ->label('Description')
                    ->columnSpan(8),

                TextInput::make('frequency')
                    ->label('Billing Frequency')
                    ->required()
                    ->numeric()
                    ->rules(['required', 'integer', 'min:0'])
                    ->columnSpan(8)
                    ->helperText('Positive integers only. Required for billing your customers every
                     N days/weeks/months/years depending on the selected frequency unit.'),

                Select::make('frequency_unit')
                    ->label('Frequency Unit')
                    ->options([
                        'day'    => 'Day',
                        'week'   => 'Week',
                        'month'  => 'Month',
                        'year'   => 'Year',
                    ])
                    ->default('month')
                    ->extraAttributes(['style' => 'width: 160px'])
                    ->helperText('This is used in combination with billing frequency to define the interval of time
                     from the end of one billing, or invoice, statement date to the next billing statement date.')
                    ->columnSpan(8),

                Toggle::make('enable_trial')
                    ->label('Enable Free Trials')
                    ->reactive()
                    ->helperText('Enable this option if you want your customers to test the booking for free prior
                     charging them a normal subscription price.')
                    ->columnSpan(8),

                TextInput::make('trial_days')
                    ->label('Trial Days')
                    ->numeric()
                    ->rules(['required', 'integer', 'min:0'])
                    ->minValue(1)
                    ->visible(fn ($get) => $get('enable_trial') === true)
                    ->helperText('Positive integers only. Your customer will not be charged a regular subscription
                     fee for using the booking in a matter of first N days from the subscription start date.')
                    ->columnSpan(8),

                Toggle::make('enable_initial_fee')
                    ->label('Charge Initial Fee')
                    ->reactive()
                    ->default(0)
                    ->helperText('Choose whether you want to charge your customers initial (one-time) subscription
                     fee or not. This will be charged only once at the moment of first purchase not affecting
                     future billing cycles.')
                    ->columnSpan(8),

                Select::make('initial_fee_type')
                    ->label('Initial Fee Type')
                    ->options([
                        'fixed'    => 'Fixed Amount',
                        'percent'  => 'Percent of Subscription Price',
                    ])
                    ->default('fixed')
                    ->visible(fn ($get) => $get('enable_initial_fee') === true)
                    ->extraAttributes(['style' => 'width: 160px'])
                    ->helperText('Fee can be either a fixed amount in the base store currency or a percent of
                     the regular price of the booking.')
                    ->columnSpan(8),

                TextInput::make('initial_fee_amount')
                    ->label('Initial Fee Amount')
                    ->numeric()
                    ->rules(['required', 'numeric', 'min:0'])
                    ->minValue(1)
                    ->visible(fn ($get) => $get('enable_initial_fee') === true)
                    ->helperText('Positive floating point numbers only.')
                    ->columnSpan(8),
            ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->label('Plan Name')->sortable(),
            Tables\Columns\TextColumn::make('frequency')->label('Billing Cycle')
                ->formatStateUsing(function ($state, $record) {
                    $unit = $record->frequency_unit;
                    $frequency = $state;

                    $unitPlural = match ($unit) {
                        'day'    => 'days',
                        'week'   => 'weeks',
                        'month'  => 'months',
                        'year'   => 'years',
                        default  => $unit,
                    };

                    if ($frequency == 1) {
                        return 'Once a ' . $unit;
                    }

                    return 'Every ' . $frequency . ' ' . $unitPlural;
                }),
            Tables\Columns\TextColumn::make('enable_trial')->label('Trial Period')
                ->formatStateUsing(fn ($state) => $state ? 'Enabled' : 'Disabled')
                ->sortable(),
            Tables\Columns\TextColumn::make('enable_initial_fee')->label('Initial Fee')
                ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No')->sortable(),
            Tables\Columns\TextColumn::make('status')
                ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Suspended')
                ->color(fn ($state) => $state ? 'success' : 'danger')
                ->weight('bold')
                ->sortable(),
        ])
        ->filters([])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptionPlans::route('/'),
            'create' => Pages\CreateSubscriptionPlan::route('/create'),
            'edit' => Pages\EditSubscriptionPlan::route('/{record}/edit'),
        ];
    }
}
