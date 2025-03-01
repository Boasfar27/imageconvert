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

        $days = [
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        ];
        
        return $days[$date->format('w')] . ', ' . 
               $date->format('d') . ' ' . 
               $months[$date->format('n') - 1] . ' ' . 
               $date->format('Y') . ' ' .
               $date->format('H:i') . ' WIB';
    }

    public static function formatSize($bytes)
    {
        if ($bytes >= 1024 * 1024 * 1024) {
            return number_format($bytes / 1024 / 1024 / 1024, 2) . ' GB';
        } elseif ($bytes >= 1024 * 1024) {
            return number_format($bytes / 1024 / 1024, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        } else {
            return number_format($bytes) . ' B';
        }
    }
} 