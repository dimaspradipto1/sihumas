<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Publikasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'user_id',
        'tahun_akademik_id',
        'pengajuan_id',
        'upload_laporan',
        'link_dokumentasi',
        'link_publikasi',
    ];

    protected static function booted()
    {
        static::creating(function ($publikasi) {
            if (empty($publikasi->slug)) {
                $title = $publikasi->pengajuan?->nama_kegiatan;
                if (!$title && $publikasi->pengajuan_id) {
                    $title = Pengajuan::find($publikasi->pengajuan_id)?->nama_kegiatan;
                }
                $publikasi->slug = static::generateUniqueSlug($title);
            }
        });

        static::updating(function ($publikasi) {
            if (empty($publikasi->slug)) {
                $title = $publikasi->pengajuan?->nama_kegiatan;
                if (!$title && $publikasi->pengajuan_id) {
                    $title = Pengajuan::find($publikasi->pengajuan_id)?->nama_kegiatan;
                }
                $publikasi->slug = static::generateUniqueSlug($title, $publikasi->id);
            }
        });
    }

    public static function generateUniqueSlug(?string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title ?? '') ?: 'publikasi';
        $slug = $base;
        $counter = 1;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $counter++;
            $slug = "{$base}-{$counter}";
        }

        return $slug;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getRouteKey()
    {
        return !empty($this->slug) ? $this->slug : (string) $this->id;
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'slug', $value)
            ->orWhere('id', $value)
            ->firstOrFail();
    }

    public function pengajuan()
    {
        // PK pengajuans.id  — FK publikasis.pengajuan_id
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }

    public function tahunAkademik()
    {
        // PK tahun_akademiks.id — FK publikasis.tahun_akademik_id
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function laporanPublikasi()
    {
        return $this->hasOne(LaporanPublikasi::class, 'publikasi_id');
    }
}
