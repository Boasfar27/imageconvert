<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImageConversion extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_name',
        'original_path',
        'converted_path',
        'original_format',
        'converted_format',
        'original_size',
        'converted_size',
        'quality',
        'user_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'original_size' => 'integer',
        'converted_size' => 'integer',
        'quality' => 'integer',
    ];

    /**
     * Get the user that owns the conversion.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the file size in a human readable format.
     */
    public function getFormattedOriginalSizeAttribute()
    {
        return $this->formatFileSize($this->original_size);
    }

    /**
     * Get the converted file size in a human readable format.
     */
    public function getFormattedConvertedSizeAttribute()
    {
        return $this->formatFileSize($this->converted_size);
    }

    /**
     * Format file size to human readable format.
     */
    private function formatFileSize($size)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        return round($size, 2) . ' ' . $units[$i];
    }
}
