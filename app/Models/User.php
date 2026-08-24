<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';
    public $timestamps = false; // legacy hanya punya created_at
    protected $guarded = ['id'];
    protected $hidden = ['password_hash'];

    protected $casts = [
        'verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Override kolom password default Laravel karena legacy pakai `password_hash`.
     */
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id')
            ->withPivot(['is_primary', 'assigned_at']);
    }

    public function wilayah(): BelongsToMany
    {
        return $this->belongsToMany(Wilayah::class, 'user_wilayah', 'user_id', 'wilayah_id')
            ->withPivot(['is_primary', 'access_type', 'assigned_at']);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function pelakuUsahaProfile(): HasOne
    {
        return $this->hasOne(PelakuUsahaProfile::class, 'user_id');
    }

    public function permohonan(): HasMany
    {
        return $this->hasMany(Permohonan::class, 'user_id');
    }

    public function permohonanDitangani(): HasMany
    {
        return $this->hasMany(Permohonan::class, 'assigned_pembina_id');
    }

    public function konsultasiTeknisDibuat(): HasMany
    {
        return $this->hasMany(KonsultasiTeknis::class, 'created_by');
    }

    public function konsultasiTeknisDitangani(): HasMany
    {
        return $this->hasMany(KonsultasiTeknis::class, 'assigned_pembina_id');
    }

    public function pembinaanProaktifDitangani(): HasMany
    {
        return $this->hasMany(PembinaanProaktif::class, 'assigned_pembina_id');
    }

    public function petugasRegistration(): HasOne
    {
        return $this->hasOne(PetugasRegistration::class, 'user_id');
    }

    public function pembinaMutuMaster(): HasOne
    {
        return $this->hasOne(PembinaMutuMaster::class, 'linked_user_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(UserNotification::class, 'user_id');
    }

    public function masterPelakuUsahaClaims(): HasMany
    {
        return $this->hasMany(UserMasterPelakuUsaha::class, 'user_id');
    }
}