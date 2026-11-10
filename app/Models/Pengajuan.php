<?php
// app/Models/Pengajuan.php
namespace App\Models;


use App\Models\Publikasi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengajuan extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'pic_petugas' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tahunAkdemik()
    {
        return $this->belongsTo(TahunAkademik::class);
    }

    public function publikasi()
    {
        return $this->hasOne(Publikasi::class, 'pengajuan_id');
    }
 
    public function kotakMasukPengajuan()
    {
        return $this->hasOne(KotakMasukPengajuan::class, 'pengajuan_id');
    }

    public function unitKegiatan()
    {
        return $this->belongsTo(UnitKegiatan::class);
    }

    public function laporanPublikasi()
    {
        return $this->hasOne(LaporanPublikasi::class);
    }

    /**
     * Scope query untuk pengajuan yang ditugaskan kepada user tertentu sebagai PIC / Petugas.
     */
    public function scopeAssignedToUser($query, $user)
    {
        if (!$user) {
            return $query->whereRaw('0 = 1');
        }

        $name = trim($user->name ?? '');
        $rawWa = trim($user->no_wa ?? '');
        $digitsWa = preg_replace('/[^0-9]/', '', $rawWa);

        if (empty($name) && empty($rawWa)) {
            return $query->whereRaw('0 = 1');
        }

        return $query->where(function ($q) use ($name, $rawWa, $digitsWa) {
            $hasClause = false;

            if (!empty($name)) {
                $q->where('petugas_ditugaskan', 'like', "%{$name}%")
                  ->orWhere('pic_petugas', 'like', "%{$name}%");
                $hasClause = true;
            }

            if (!empty($rawWa)) {
                $action = $hasClause ? 'orWhere' : 'where';
                $q->{$action}('no_wa_tujuan', 'like', "%{$rawWa}%")
                  ->orWhere('pic_petugas', 'like', "%{$rawWa}%");
                $hasClause = true;
            }

            if (strlen($digitsWa) >= 8) {
                $suffixWa = substr($digitsWa, -8);
                $q->orWhere('no_wa_tujuan', 'like', "%{$suffixWa}%")
                  ->orWhere('pic_petugas', 'like', "%{$suffixWa}%");
            }
        });
    }
}
