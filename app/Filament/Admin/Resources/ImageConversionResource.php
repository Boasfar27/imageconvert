<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ImageConversionResource\Pages;
use App\Filament\Admin\Resources\ImageConversionResource\RelationManagers;
use App\Models\ImageConversion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ImageConversionResource extends Resource
{
    protected static ?string $model = ImageConversion::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('original_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('original_path')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('converted_path')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('original_format')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('converted_format')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('original_size')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('converted_size')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('quality')
                    ->required()
                    ->numeric()
                    ->default(80),
                Forms\Components\TextInput::make('size_reduction')
                    ->numeric(),
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('original_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('original_path')
                    ->searchable(),
                Tables\Columns\TextColumn::make('converted_path')
                    ->searchable(),
                Tables\Columns\TextColumn::make('original_format')
                    ->searchable(),
                Tables\Columns\TextColumn::make('converted_format')
                    ->searchable(),
                Tables\Columns\TextColumn::make('original_size')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('converted_size')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quality')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('size_reduction')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
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
            'index' => Pages\ListImageConversions::route('/'),
            'create' => Pages\CreateImageConversion::route('/create'),
            'edit' => Pages\EditImageConversion::route('/{record}/edit'),
        ];
    }
}
