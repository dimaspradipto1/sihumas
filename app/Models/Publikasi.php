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
        'status_validasi',
        'catatan_validasi',
        'validated_by',
        'validated_at',
    ];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($publikasi) {
            if (empty($publikasi->tahun_akademik_id)) {
                $publikasi->tahun_akademik_id = TahunAkademik::getActive()?->id;
            }

            if (empty($publikasi->slug)) {
                $title = $publikasi->pengajuan?->nama_kegiatan;
                if (!$title && $publikasi->pengajuan_id) {
                    $title = Pengajuan::find($publikasi->pengajuan_id)?->nama_kegiatan;
                }
                $publikasi->slug = static::generateUniqueSlug($title);
            }
        });

        static::updating(function ($publikasi) {
            if (empty($publikasi->tahun_akademik_id)) {
                $publikasi->tahun_akademik_id = TahunAkademik::getActive()?->id;
            }

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

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function laporanPublikasi()
    {
        return $this->hasOne(LaporanPublikasi::class, 'publikasi_id');
    }

    public function isPendingValidation(): bool
    {
        return $this->status_validasi === 'menunggu_validasi';
    }

    public function isApproved(): bool
    {
        return $this->status_validasi === 'disetujui';
    }

    public function needsRevision(): bool
    {
        return $this->status_validasi === 'perlu_revisi';
    }

    public function isBelumDilaporkan(): bool
    {
        return empty($this->status_validasi) || $this->status_validasi === 'belum_dilaporkan';
    }

    /**
     * Mengambil daftar link publikasi dalam bentuk array (mendukung JSON atau string)
     */
    public function getLinkPublikasiArrayAttribute(): array
    {
        if (empty($this->link_publikasi)) {
            return [];
        }

        $val = trim($this->link_publikasi);

        if (str_starts_with($val, '[') && str_ends_with($val, ']')) {
            $decoded = json_decode($val, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return array_values(array_filter(array_map('trim', $decoded)));
            }
        }

        $lines = preg_split('/[\r\n,]+/', $val);
        $lines = array_values(array_filter(array_map('trim', $lines)));

        return !empty($lines) ? $lines : [$val];
    }
}
