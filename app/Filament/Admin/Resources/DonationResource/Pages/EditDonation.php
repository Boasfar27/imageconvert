<?php

namespace App\Filament\Admin\Resources\DonationResource\Pages;

use App\Filament\Admin\Resources\DonationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\View\View;

class EditDonation extends EditRecord
{
    protected static string $resource = DonationResource::class;

    public function beforeValidate(): void
    {
        // Ambil record ID dari parameter rute
        $recordId = request()->route('record');
        
        // Redirect ke halaman bukti pembayaran kustom
        if ($recordId) {
            redirect()->to(route('donations.view-proof', $recordId))->send();
            exit;
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
