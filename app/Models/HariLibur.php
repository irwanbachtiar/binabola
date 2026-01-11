<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HariLibur extends Model
{
    protected $table = 'hari_libur';
    
    protected $fillable = [
        'tanggal',
        'keterangan',
        'jenis',
        'aktif',
    ];
    
    protected $casts = [
        'tanggal' => 'date',
        'aktif' => 'boolean',
    ];
    
    /**
     * Check if a date is a holiday
     */
    public static function isHoliday($tanggal)
    {
        return self::where('tanggal', $tanggal)
            ->where('aktif', true)
            ->exists();
    }
    
    /**
     * Get holidays between two dates
     */
    public static function getHolidaysBetween($startDate, $endDate)
    {
        return self::whereBetween('tanggal', [$startDate, $endDate])
            ->where('aktif', true)
            ->get();
    }
}
