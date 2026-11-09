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
}
