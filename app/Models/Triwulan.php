<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Triwulan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'bulan_mulai',
        'bulan_selesai',
        'is_active',
    ];

    protected $casts = [
        'bulan_mulai' => 'integer',
        'bulan_selesai' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::saving(function ($triwulan) {
            if ($triwulan->is_active) {
                static::where('id', '!=', $triwulan->id ?? 0)->update(['is_active' => false]);
            }
        });
    }

    /**
     * Cek apakah suatu bulan (1-12) termasuk dalam periode TW ini.
     */
    public function containsMonth(int $month): bool
    {
        if ($this->bulan_mulai <= $this->bulan_selesai) {
            return $month >= $this->bulan_mulai && $month <= $this->bulan_selesai;
        }

        // Lintas tahun, misal TW2: Des (12) s/d Feb (2)
        return $month >= $this->bulan_mulai || $month <= $this->bulan_selesai;
    }

    /**
     * Cari record TW yang sesuai dengan bulan tertentu (default bulan berjalan).
     */
    public static function resolveForMonth(?int $month = null): ?self
    {
        $month = $month ?? (int) Carbon::now()->month;

        $all = static::orderBy('id')->get();
        foreach ($all as $tw) {
            if ($tw->containsMonth($month)) {
                return $tw;
            }
        }

        return static::where('is_active', true)->first() ?? static::first();
    }

    /**
     * Sinkronkan status aktif otomatis sesuai bulan berjalan.
     */
    public static function syncActiveByCurrentMonth(?int $month = null): ?self
    {
        $target = static::resolveForMonth($month);
        if (!$target) {
            return null;
        }

        if (!$target->is_active) {
            static::where('id', '!=', $target->id)->update(['is_active' => false]);
            $target->update(['is_active' => true]);
        }

        return $target;
    }

    /**
     * Mendapatkan TW yang sedang aktif (otomatis mengikuti bulan berjalan).
     */
    public static function getActive(): ?self
    {
        return static::syncActiveByCurrentMonth();
    }

    /**
     * Hitung rentang tanggal [start, end] untuk kode/record TW dan tahun tertentu.
     *
     * @return array{0: Carbon, 1: Carbon}
     */
    public function getDateRangeForYear(int $year): array
    {
        $start = Carbon::createFromDate($year, $this->bulan_mulai, 1)->startOfDay();
        $endYear = $this->bulan_mulai > $this->bulan_selesai ? $year + 1 : $year;
        $end = Carbon::createFromDate($endYear, $this->bulan_selesai, 1)->endOfMonth()->endOfDay();

        return [$start, $end];
    }

    public function publikasis()
    {
        return $this->hasMany(Publikasi::class, 'triwulan_id');
    }
}
