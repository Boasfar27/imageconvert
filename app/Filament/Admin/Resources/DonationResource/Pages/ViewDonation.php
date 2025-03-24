<?php

namespace App\Filament\Admin\Resources\DonationResource\Pages;

use App\Filament\Admin\Resources\DonationResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewDonation extends ViewRecord
{
    protected static string $resource = DonationResource::class;
    
    public function beforeMount(): void
    {
        // Ambil record ID dari parameter rute
        $recordId = request()->route('record');
        
        // Redirect ke halaman bukti pembayaran kustom
        if ($recordId) {
            redirect()->to(route('donations.view-proof', $recordId))->send();
            exit;
        }
    }
    
    public function getTitle(): string|Htmlable
    {
        return __('View Donation');
    }
} 