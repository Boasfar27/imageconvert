<?php

namespace App\Filament\Admin\Resources\ImageConversionResource\Pages;

use App\Filament\Admin\Resources\ImageConversionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImageConversion extends EditRecord
{
    protected static string $resource = ImageConversionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
