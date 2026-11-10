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
        'fakultas',
        'unit_kegiatan_id',
        'is_admin',
        'is_humas',
        'is_rektorat',
        'is_feb',
        'is_fst',
        'is_fikes',
        'is_users'
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
            $badges[] = '<span class="badge px-2 py-1 me-1 text-white" style="background-color: #dc3545 !important; color: #ffffff !important; font-size: 11px; font-weight: 600;">Admin</span>';
        }
        if ($this->is_humas) {
            $badges[] = '<span class="badge px-2 py-1 me-1 text-white" style="background-color: #0d6efd !important; color: #ffffff !important; font-size: 11px; font-weight: 600;"><i class="fa-solid fa-bullhorn me-1"></i>Humas</span>';
        }
        if ($this->is_rektorat) {
            $badges[] = '<span class="badge px-2 py-1 me-1 text-white" style="background-color: #6f42c1 !important; color: #ffffff !important; font-size: 11px; font-weight: 600;">Rektorat</span>';
        }
        if ($this->is_fst) {
            $badges[] = '<span class="badge px-2 py-1 me-1 text-white" style="background-color: #0284c7 !important; color: #ffffff !important; font-size: 11px; font-weight: 600;">FST</span>';
        }
        if ($this->is_feb) {
            $badges[] = '<span class="badge px-2 py-1 me-1" style="background-color: #ffc107 !important; color: #1e293b !important; font-size: 11px; font-weight: 600;">FEB</span>';
        }
        if ($this->is_fikes) {
            $badges[] = '<span class="badge px-2 py-1 me-1 text-white" style="background-color: #10b981 !important; color: #ffffff !important; font-size: 11px; font-weight: 600;">FIKES</span>';
        }
        if ($this->is_users && empty($badges)) {
            $badges[] = '<span class="badge px-2 py-1 me-1 text-white" style="background-color: #4b5563 !important; color: #ffffff !important; font-size: 11px; font-weight: 600;">Pengguna</span>';
        } elseif (empty($badges)) {
            $badges[] = '<span class="badge px-2 py-1 me-1 text-white" style="background-color: #4b5563 !important; color: #ffffff !important; font-size: 11px; font-weight: 600;">Pengguna</span>';
        }
        return implode(' ', $badges);
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
}
