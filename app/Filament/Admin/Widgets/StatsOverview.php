<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Donation;
use App\Models\ImageConversion;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Total users
        $totalUsers = User::count();
        $premiumUsers = User::where('role', 1)->count();
        $premiumPercentage = $totalUsers > 0 ? round(($premiumUsers / $totalUsers) * 100) : 0;
        
        // Donations
        $pendingDonations = Donation::where('status', 'pending')->count();
        $totalDonationAmount = Donation::where('status', 'approved')->sum('amount');
        
        // Conversions
        $totalConversions = ImageConversion::count();
        $todayConversions = ImageConversion::whereDate('created_at', today())->count();
        $totalSizeReduction = ImageConversion::sum(\DB::raw('original_size - converted_size'));
        $sizeReductionMB = round($totalSizeReduction / (1024 * 1024), 2);
        
        return [
            Stat::make('Total Users', $totalUsers)
                ->description($premiumPercentage . '% premium users')
                ->descriptionIcon('heroicon-m-user')
                ->color('info')
                ->chart([7, 4, 5, $totalUsers]),
                
            Stat::make('Pending Donations', $pendingDonations)
                ->description('Require approval')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color($pendingDonations > 0 ? 'warning' : 'success'),
                
            Stat::make('Total Revenue', 'Rp ' . number_format($totalDonationAmount, 0, ',', '.'))
                ->description('From approved donations')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
                
            Stat::make('Total Conversions', $totalConversions)
                ->description($todayConversions . ' today')
                ->descriptionIcon('heroicon-m-photo')
                ->color('primary'),
                
            Stat::make('Storage Saved', $sizeReductionMB . ' MB')
                ->description('Total size reduction')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('success'),
        ];
    }
} 