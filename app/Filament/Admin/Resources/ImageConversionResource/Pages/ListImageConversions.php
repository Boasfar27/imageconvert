<?php

namespace App\Filament\Admin\Resources\ImageConversionResource\Pages;

use App\Filament\Admin\Resources\ImageConversionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListImageConversions extends ListRecords
{
    protected static string $resource = ImageConversionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
