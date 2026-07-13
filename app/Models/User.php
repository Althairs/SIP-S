<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method bool hasRole(string|array $roles)
 */ class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'jurusan_id',
        'prodi_id',
        'role',
        'nip',
        'nim',
        'nomor_hp',
        'alamat',
        'foto',
        'kepakaran_id',
        'is_active',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relasi ke Jurusan
     */
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    /**
     * Relasi ke Prodi
     */
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function kepakaran()
    {
        return $this->belongsTo(Kepakaran::class);
    }

    public function bidangKeahlians()
    {
        return $this->belongsToMany(BidangKeahlian::class, 'dosen_bidang_keahlian', 'user_id', 'bidang_keahlian_id')
            ->withTimestamps();
    }

    public function kuota()
    {
        return $this->hasOne(KuotaDosen::class, 'dosen_id');
    }

    /**
     * Scope untuk user aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk role tertentu
     */
    public function scopeRole($query, string|array $role)
    {
        return $query->whereHas('roles', function ($q) use ($role) {
            if (is_array($role)) {
                $q->whereIn('name', $role);

                return;
            }

            $q->where('name', $role);
        });
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class, 'mahasiswa_id');
    }
}

