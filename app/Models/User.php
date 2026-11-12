<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'no_wa',
        'password',
        'unit_kegiatan_id',
        'is_admin',
        'is_humas',
        'is_rektorat',
        'is_feb',
        'is_fst',
        'is_fikes',
        'is_users',
        'pengajuan_read_at',
        'kotak_masuk_read_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'pengajuan_read_at' => 'datetime',
        'kotak_masuk_read_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'is_humas' => 'boolean',
        'is_rektorat' => 'boolean',
        'is_feb' => 'boolean',
        'is_fst' => 'boolean',
        'is_fikes' => 'boolean',
        'is_users' => 'boolean',
    ];

    /**
     * Get HTML badges representing user roles.
     */
    public function getRoleBadgesAttribute(): string
    {
        $badges = [];
        if ($this->is_admin) {
            $badges[] = '<span class="badge px-2 py-1 text-white shadow-sm" style="background-color: #dc3545 !important; font-size: 11px; font-weight: 600;"><i class="fa-solid fa-shield-halved me-1"></i>Admin Humas</span>';
        } elseif ($this->is_humas) {
            $badges[] = '<span class="badge px-2 py-1 text-white shadow-sm" style="background-color: #0d6efd !important; font-size: 11px; font-weight: 600;"><i class="fa-solid fa-bullhorn me-1"></i>Petugas Humas</span>';
        } else {
            $badges[] = '<span class="badge px-2 py-1 text-white shadow-sm" style="background-color: #198754 !important; font-size: 11px; font-weight: 600;"><i class="fa-solid fa-building me-1"></i>Pengusul Unit</span>';
        }

        return implode(' ', $badges);
    }

    /**
     * Get friendly display name of user's primary role.
     */
    public function getRoleLabelAttribute(): string
    {
        if ($this->is_admin) {
            return 'Admin Humas';
        }
        if ($this->is_humas) {
            return 'Petugas Humas';
        }
        $sub = $this->unitKegiatan?->nama_sub;
        return $sub ? "Pengusul ({$sub})" : 'Pengusul Unit';
    }

    /**
     * Get unique role identifier key ('admin', 'humas', or 'operator').
     */
    public function getRoleKeyAttribute(): string
    {
        if ($this->is_admin) {
            return 'admin';
        }
        if ($this->is_humas) {
            return 'humas';
        }
        return 'operator';
    }

    /**
     * Scope a query to only include Humas staff.
     */
    public function scopeHumas($query)
    {
        return $query->where('is_humas', true);
    }

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class);
    }

    public function publikasi()
    {
        return $this->hasMany(Publikasi::class);
    }

    public function laporanPublikasi()
    {
        return $this->hasMany(LaporanPublikasi::class);
    }

    public function pegawai()
    {
        return $this->hasOne(Pegawai::class);
    }

    public function unitKegiatan()
    {
        return $this->belongsTo(UnitKegiatan::class, 'unit_kegiatan_id');
    }

    public function getFakultasAttribute(): string
    {
        return $this->unitKegiatan?->unit_kegiatan ?? '';
    }
}
