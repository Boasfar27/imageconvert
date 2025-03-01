<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function formatIndonesian($date)
    {
        if (!$date) return '';

        Carbon::setLocale('id');
        $date = Carbon::parse($date)->setTimezone('Asia/Jakarta');
        
        $months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        return $date->format('d') . ' ' . 
               $months[$date->format('n') - 1] . ' ' . 
               $date->format('Y H:i');
    }
} 