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
}
