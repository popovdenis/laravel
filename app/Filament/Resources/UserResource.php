<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Hash;

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
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(6),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(6),

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
                    ->columnSpan(4),

                Forms\Components\TextInput::make('new_password')
                    ->password()
                    ->label('New Password')
                    ->requiredWith('change_password')
                    ->dehydrated(false)
                    ->visible(fn ($get) => $get('change_password'))
                    ->columnSpan(4),

                Forms\Components\TextInput::make('new_password_confirmation')
                    ->password()
                    ->label('Confirm New Password')
                    ->requiredWith('change_password')
                    ->dehydrated(false)
                    ->same('new_password')
                    ->visible(fn ($get) => $get('change_password'))
                    ->columnSpan(4),

                Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload()
                    ->columnSpan(12),
            ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
