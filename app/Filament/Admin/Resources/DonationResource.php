<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DonationResource\Pages;
use App\Filament\Admin\Resources\DonationResource\RelationManagers;
use App\Models\Donation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Forms\Components\ViewField;
use Filament\Tables\Columns\ViewColumn;
use App\Notifications\DonationStatusChanged;
use App\Models\User;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;

class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    
    protected static ?string $navigationLabel = 'Donations & Premium';
    
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                Forms\Components\Select::make('type')
                    ->options([
                        'limit_increase' => 'Limit Increase',
                        'premium_upgrade' => 'Premium Upgrade',
                    ])
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('admin_notes')
                    ->rows(3)
                    ->placeholder('Optional notes for approval/rejection'),
                Forms\Components\Section::make('Payment Proof')
                    ->description('Preview the payment proof image')
                    ->schema([
                        Forms\Components\Placeholder::make('payment_proof_status')
                            ->content(fn ($record) => $record && $record->payment_proof ? 'Payment proof available' : 'No payment proof uploaded')
                            ->columnSpan(2),
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('view_image')
                                ->label('View Payment Proof')
                                ->url(fn ($record) => $record && $record->payment_proof ? route('donations.view-proof', $record->id) : null)
                                ->openUrlInNewTab()
                                ->color('primary')
                                ->icon('heroicon-o-photo')
                                ->visible(fn ($record) => $record && $record->payment_proof)
                                ->hidden(fn ($record) => !$record || !$record->payment_proof),
                        ])
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
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
                Tables\Columns\TextColumn::make('payment_proof')
                    ->label('Proof')
                    ->formatStateUsing(function ($record) {
                        if ($record->payment_proof) {
                            return 'Available';
                        }
                        return 'No proof';
                    })
                    ->url(fn ($record) => $record->payment_proof ? route('donations.payment-proof', $record->id) : null)
                    ->openUrlInNewTab()
                    ->color('primary')
                    ->icon('heroicon-o-photo'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                SelectFilter::make('type')
                    ->options([
                        'limit_increase' => 'Limit Increase',
                        'premium_upgrade' => 'Premium Upgrade',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->url(fn (Donation $record) => route('donations.view-proof', $record->id))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-eye'),
                Tables\Actions\Action::make('edit')
                    ->label('Edit')
                    ->url(fn (Donation $record) => route('donations.view-proof', $record->id))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-pencil'),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(function (Donation $record) {
                        return $record->status === 'pending';
                    })
                    ->action(function (Donation $record) {
                        // Update donation status
                        $record->status = 'approved';
                        $record->save();
                        
                        // Get the user
                        $user = $record->user;
                        
                        // Update user based on donation type
                        if ($record->type === 'limit_increase') {
                            $user->addConversionLimit($user->getLimitIncreaseAmount());
                            $notificationMsg = 'The donation has been approved and conversion limits have been added to the user.';
                        } else {
                            $user->role = 1;
                            $user->save();
                            $notificationMsg = 'The donation has been approved and the user has been upgraded to premium.';
                        }
                        
                        // Send notification
                        $user->notify(new DonationStatusChanged($record));
                        
                        // Show notification
                        Notification::make()
                            ->success()
                            ->title('Donation Approved')
                            ->body($notificationMsg . ' An email notification has been sent to the user.')
                            ->send();
                    }),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(function (Donation $record) {
                        return $record->status === 'pending';
                    })
                    ->form([
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Rejection Reason')
                            ->required()
                            ->maxLength(500)
                            ->helperText('This reason will be sent to the user via email.'),
                    ])
                    ->action(function (Donation $record, array $data) {
                        // Update donation status
                        $record->status = 'rejected';
                        $record->admin_notes = $data['admin_notes'];
                        $record->save();
                        
                        // Send notification
                        $record->user->notify(new DonationStatusChanged($record));
                        
                        // Show notification
                        Notification::make()
                            ->success()
                            ->title('Donation Rejected')
                            ->body('The donation has been rejected. An email notification has been sent to the user.')
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approveBulk')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(function (Collection $records) {
                            $processed = 0;
                            
                            foreach ($records as $record) {
                                // Skip non-pending donations
                                if ($record->status !== 'pending') {
                                    continue;
                                }
                                
                                // Update donation status
                                $record->status = 'approved';
                                $record->save();
                                
                                // Get the user
                                $user = $record->user;
                                
                                // Update user based on donation type
                                if ($record->type === 'limit_increase') {
                                    $user->addConversionLimit($user->getLimitIncreaseAmount());
                                } else {
                                    $user->role = 1;
                                    $user->save();
                                }
                                
                                // Send notification
                                $user->notify(new DonationStatusChanged($record));
                                
                                $processed++;
                            }
                            
                            // Show notification
                            Notification::make()
                                ->success()
                                ->title('Donations Approved')
                                ->body("Successfully processed {$processed} donations. Email notifications have been sent to users.")
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
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
            'index' => Pages\ListDonations::route('/'),
            'create' => Pages\CreateDonation::route('/create'),
            'view' => Pages\ViewDonation::route('/{record}/view'),
            'edit' => Pages\EditDonation::route('/{record}/edit'),
        ];
    }

    public static function getRecordPageUrl($record): string
    {
        return route('donations.view-proof', $record);
    }

    public static function getRecordRouteKeyName(): string
    {
        return 'id';
    }
}
