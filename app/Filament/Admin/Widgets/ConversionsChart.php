<?php

namespace App\Filament\Admin\Widgets;

use App\Models\ImageConversion;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ConversionsChart extends ChartWidget
{
    protected static ?string $heading = 'Conversions';
    
    protected static ?int $sort = 2;
    
    protected function getData(): array
    {
        $days = 30;
        
        $records = ImageConversion::select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
            ])
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        $labels = [];
        $data = [];
        
        // Create an array with all dates in the range
        for ($i = $days; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d M');
            
            $record = $records->firstWhere('date', $date);
            $data[] = $record ? $record->count : 0;
        }
            
        return [
            'datasets' => [
                [
                    'label' => 'Conversions',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                ],
            ],
            'labels' => $labels,
        ];
    }
    
    protected function getType(): string
    {
        return 'line';
    }
} 