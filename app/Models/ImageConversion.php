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
     * Get the user that owns the conversion.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
