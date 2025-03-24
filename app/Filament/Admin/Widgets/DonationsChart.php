<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Donation;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DonationsChart extends ChartWidget
{
    protected static ?string $heading = 'Donations';
    
    protected static ?int $sort = 3;
    
    protected function getData(): array
    {
        $days = 30;
        
        $countRecords = Donation::select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
            ])
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        $amountRecords = Donation::select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total'),
            ])
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        $labels = [];
        $countData = [];
        $amountData = [];
        
        // Create an array with all dates in the range
        for ($i = $days; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d M');
            
            $countRecord = $countRecords->firstWhere('date', $date);
            $countData[] = $countRecord ? $countRecord->count : 0;
            
            $amountRecord = $amountRecords->firstWhere('date', $date);
            $amountData[] = $amountRecord ? $amountRecord->total / 1000 : 0; // Convert to thousands
        }
            
        return [
            'datasets' => [
                [
                    'label' => 'Number of Donations',
                    'data' => $countData,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.5)',
                    'borderColor' => 'rgb(34, 197, 94)',
                ],
                [
                    'label' => 'Amount (Rp thousands)',
                    'data' => $amountData,
                    'backgroundColor' => 'rgba(249, 115, 22, 0.5)',
                    'borderColor' => 'rgb(249, 115, 22)',
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $labels,
        ];
    }
    
    protected function getType(): string
    {
        return 'bar';
    }
    
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Count',
                    ],
                    'beginAtZero' => true,
                ],
                'y1' => [
                    'position' => 'right',
                    'title' => [
                        'display' => true,
                        'text' => 'Amount (Rp thousands)',
                    ],
                    'beginAtZero' => true,
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
            ],
        ];
    }
} 