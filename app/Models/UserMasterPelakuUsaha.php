<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserMasterPelakuUsaha extends Model
{
    protected $table = 'user_master_pelaku_usaha';
    protected $guarded = ['id'];

    protected $casts = [
        'is_primary' => 'boolean',
        'verified_at' => 'datetime',
        'decided_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function masterPelaku(): BelongsTo
    {
        return $this->belongsTo(MasterPelakuUsaha::class, 'master_pelaku_id');
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(PelakuUsahaProfile::class, 'profile_id');
    }

    public function handlingWilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'handling_wilayah_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function history(): HasMany
    {
        return $this->hasMany(UserMasterPelakuUsahaHistory::class, 'claim_id');
    }
}