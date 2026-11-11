<?php

namespace App\Models;

use App\Models\Publikasi;
use App\Models\LaporanPublikasi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TahunAkademik extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun_akademik',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::saving(function ($tahunAkademik) {
            if ($tahunAkademik->is_active) {
                static::where('id', '!=', $tahunAkademik->id ?? 0)->update(['is_active' => false]);
            }
        });
    }

    /**
     * Mendapatkan Tahun Akademik yang sedang aktif (atau record terbaru sebagai fallback).
     */
    public static function getActive(): ?self
    {
        return static::where('is_active', true)->first() ?? static::latest('id')->first();
    }

    public function publikasis()
    {
        return $this->hasMany(Publikasi::class, 'tahun_akademik_id');
    }

    public function laporanPublikasis()
    {
        return $this->hasMany(LaporanPublikasi::class);
    }
}
