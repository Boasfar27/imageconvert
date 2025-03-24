<?php

namespace App\Filament\Admin\Resources\OptionaResource\Widgets;

use Filament\Widgets\ChartWidget;

class DonationStats extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
