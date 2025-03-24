<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'Users';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('google_id')
                    ->maxLength(255)
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\TextInput::make('avatar')
                    ->maxLength(255)
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->maxLength(255),
                Forms\Components\Select::make('role')
                    ->required()
                    ->options([
                        0 => 'Regular User (Limited)',
                        1 => 'Premium User (Unlimited)',
                        2 => 'Admin',
                    ])
                    ->default(0),
                Forms\Components\TextInput::make('limit_conversions')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(50)
                    ->visible(fn (Forms\Get $get) => (int) $get('role') === 0),
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
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        0 => 'gray',
                        1 => 'success',
                        2 => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        0 => 'Regular User',
                        1 => 'Premium User',
                        2 => 'Admin',
                        default => 'Unknown',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('limit_conversions')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state, $record) => $record->role > 0 ? 'Unlimited' : $state),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('add_limit')
                    ->label('Add 50 Conversions')
                    ->icon('heroicon-o-plus')
                    ->color('success')
                    ->visible(fn ($record) => $record->role === 0)
                    ->action(function (User $record) {
                        $record->addConversionLimit(50);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('addLimitBulk')
                        ->label('Add 50 Conversions')
                        ->icon('heroicon-o-plus')
                        ->action(function (Collection $records) {
                            $records->each(function ($record) {
                                if ($record->role === 0) {
                                    $record->addConversionLimit(50);
                                }
                            });
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ImageConversionsRelationManager::class,
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
