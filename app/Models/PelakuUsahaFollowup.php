<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PelakuUsahaFollowup extends Model
{
    protected $table = 'pelaku_usaha_followups';
    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'verified_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(PelakuUsahaProfile::class, 'profile_id');
    }

    public function masterPelaku(): BelongsTo
    {
        return $this->belongsTo(MasterPelakuUsaha::class, 'master_pelaku_id');
    }

    public function pembinaan(): BelongsTo
    {
        return $this->belongsTo(PembinaanProaktif::class, 'pembinaan_id');
    }

    public function permohonan(): BelongsTo
    {
        return $this->belongsTo(Permohonan::class, 'permohonan_id');
    }

    public function konsultasiTeknis(): BelongsTo
    {
        return $this->belongsTo(KonsultasiTeknis::class, 'konsultasi_teknis_id');
    }

    public function assignedPembina(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_pembina_id');
    }

    public function evidence(): HasMany
    {
        return $this->hasMany(PelakuUsahaFollowupEvidence::class, 'followup_id');
    }

    public function history(): HasMany
    {
        return $this->hasMany(PelakuUsahaFollowupHistory::class, 'followup_id');
    }
}