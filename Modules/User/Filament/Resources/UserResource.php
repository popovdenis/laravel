<?php

namespace Modules\User\Filament\Resources;

use Modules\User\Filament\Resources\UserResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\User\Models\User;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Members';
    protected static ?string $breadcrumb = 'Members';
    protected static ?string $navigationLabel = 'All Users';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(12)->schema([
                Forms\Components\TextInput::make('firstname')
                    ->label('First Name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(6),

                Forms\Components\TextInput::make('middlename')
                    ->label('Middle Name/Initial')
                    ->maxLength(255)
                    ->columnSpan(6),

                Forms\Components\TextInput::make('lastname')
                    ->label('Last Name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(6),

                Forms\Components\TextInput::make('prefix')
                    ->label('Name Prefix')
                    ->columnSpan(6),

                Forms\Components\TextInput::make('suffix')
                    ->label('Name Suffix')
                    ->maxLength(255)
                    ->columnSpan(6),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(6),

                Forms\Components\DatePicker::make('dob')
                    ->label('Date of Birth')
                    ->native(false)
                    ->columnSpan(6),

                Select::make('gender')
                    ->label('Gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Not Specified',
                    ])
                    ->dehydrated(false)
                    ->columnSpan(6),

                Select::make('timeZoneId')
                    ->label('User Timezone ID')
                    ->placeholder('-- Please Select --')
                    ->options(collect(\DateTimeZone::listIdentifiers())
                        ->mapWithKeys(fn($tz) => [$tz => $tz])
                        ->toArray())
                    ->required()
                    ->columnSpan(6),

                Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload()
                    ->extraAttributes(['style' => 'width: 200px'])
                    ->columnSpanFull(),

                Forms\Components\Toggle::make('change_password')
                    ->label('Change Password')
                    ->reactive()
                    ->dehydrated(false)
                    ->columnSpan(12),

                Forms\Components\TextInput::make('old_password')
                    ->password()
                    ->label('Current Password')
                    ->dehydrated(false)
                    ->visible(fn ($get) => $get('change_password'))
                    ->columnSpan(6),

                Forms\Components\TextInput::make('new_password')
                    ->password()
                    ->label('New Password')
                    ->requiredWith('change_password')
                    ->dehydrated(false)
                    ->visible(fn ($get) => $get('change_password'))
                    ->columnSpan(6),

                Forms\Components\TextInput::make('new_password_confirmation')
                    ->password()
                    ->label('Confirm New Password')
                    ->requiredWith('change_password')
                    ->dehydrated(false)
                    ->same('new_password')
                    ->visible(fn ($get) => $get('change_password'))
                    ->columnSpan(6),
            ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('firstname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lastname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => UserResource\Pages\ListUsers::route('/'),
            'create' => UserResource\Pages\CreateUser::route('/create'),
            'edit' => UserResource\Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
