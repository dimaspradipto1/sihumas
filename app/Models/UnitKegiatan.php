<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKegiatan extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'unit_kegiatan_id');
    }

    public static function getOrderedList()
    {
        $query = static::query();
        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn('unit_kegiatans', 'kategori')) {
                $query->orderBy('kategori');
            }
        } catch (\Throwable $e) {
            // Fallback jika terjadi kendala pada schema check
        }
        return $query->orderBy('unit_kegiatan')->get();
    }

    public function getNamaSubAttribute(): string
    {
        $kategori = $this->kategori ?? null;
        if ($kategori && str_starts_with($this->unit_kegiatan, $kategori . ' - ')) {
            return substr($this->unit_kegiatan, strlen($kategori . ' - '));
        }
        return $this->unit_kegiatan;
    }
}
