<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ImageConversionsRelationManager extends RelationManager
{
    protected static string $relationship = 'imageConversions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('original_name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('original_name')
            ->columns([
                Tables\Columns\TextColumn::make('original_name')
                    ->label('Original Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('converted_name')
                    ->label('Converted Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('from_format')
                    ->label('From')
                    ->searchable(),
                Tables\Columns\TextColumn::make('to_format')
                    ->label('To')
                    ->searchable(),
                Tables\Columns\TextColumn::make('original_size')
                    ->label('Original Size')
                    ->formatStateUsing(fn ($state) => formatBytes($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('converted_size')
                    ->label('Converted Size')
                    ->formatStateUsing(fn ($state) => formatBytes($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
} 