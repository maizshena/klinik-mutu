<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PembinaanProaktif extends Model
{
    protected $table = 'pembinaan_proaktif';
    protected $guarded = ['id'];

    protected $casts = [
        'directive_date' => 'date',
        'target_date' => 'date',
        'assigned_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(PelakuUsahaProfile::class, 'profile_id');
    }

    public function targetProvince(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'target_province_id');
    }

    public function targetDistrict(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'target_district_id');
    }

    public function assignedPembina(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_pembina_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(PembinaanProaktifAttachment::class, 'pembinaan_id');
    }

    public function history(): HasMany
    {
        return $this->hasMany(PembinaanProaktifHistory::class, 'pembinaan_id');
    }

    public function teamMembers(): HasMany
    {
        return $this->hasMany(PembinaanProaktifTeamMember::class, 'pembinaan_id');
    }

    public function followups(): HasMany
    {
        return $this->hasMany(PelakuUsahaFollowup::class, 'pembinaan_id');
    }
}