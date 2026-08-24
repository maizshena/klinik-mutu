<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterPelakuUsaha extends Model
{
    protected $table = 'master_pelaku_usaha';
    const CREATED_AT = 'imported_at';
    const UPDATED_AT = 'updated_at';
    protected $guarded = ['id'];

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'provinsi_wilayah_id');
    }

    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'kabupaten_wilayah_id');
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'kecamatan_wilayah_id');
    }

    public function linkedProfile(): BelongsTo
    {
        return $this->belongsTo(PelakuUsahaProfile::class, 'linked_profile_id');
    }

    public function importBatch(): BelongsTo
    {
        return $this->belongsTo(PelakuUsahaImportBatch::class, 'imported_batch_id');
    }

    public function units(): HasMany
    {
        return $this->hasMany(MasterPelakuUsahaUnit::class, 'master_pelaku_id');
    }

    public function userClaims(): HasMany
    {
        return $this->hasMany(UserMasterPelakuUsaha::class, 'master_pelaku_id');
    }

    public function followups(): HasMany
    {
        return $this->hasMany(PelakuUsahaFollowup::class, 'master_pelaku_id');
    }
}