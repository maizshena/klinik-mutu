<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PelakuUsahaProfile extends Model
{
    protected $table = 'pelaku_usaha_profiles';
    protected $guarded = ['id'];

    protected $casts = [
        'merged_at' => 'datetime',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'provinsi_id');
    }

    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'kabupaten_id');
    }

    public function mergedInto(): BelongsTo
    {
        return $this->belongsTo(PelakuUsahaProfile::class, 'merged_into_profile_id');
    }

    public function accountInvitations(): HasMany
    {
        return $this->hasMany(PelakuUsahaAccountInvitation::class, 'profile_id');
    }

    public function followups(): HasMany
    {
        return $this->hasMany(PelakuUsahaFollowup::class, 'profile_id');
    }

    public function pembinaanProaktif(): HasMany
    {
        return $this->hasMany(PembinaanProaktif::class, 'profile_id');
    }

    public function masterPelakuUsahaClaims(): HasMany
    {
        return $this->hasMany(UserMasterPelakuUsaha::class, 'profile_id');
    }
}