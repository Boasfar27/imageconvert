<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Donation;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestDonations extends BaseWidget
{
    protected static ?int $sort = 4;
    
    protected int | string | array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Donation::query()
                    ->with(['user'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'limit_increase' => 'info',
                        'premium_upgrade' => 'success',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'limit_increase' => 'Limit Increase',
                        'premium_upgrade' => 'Premium Upgrade',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('amount')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\ImageColumn::make('payment_proof')
                    ->label('Proof')
                    ->circular()
                    ->size(40)
                    ->visibility('public'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (Donation $record): string => route('donations.view-proof', $record)),
            ]);
    }
} 