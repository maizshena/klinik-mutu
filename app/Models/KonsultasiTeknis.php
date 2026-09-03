<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KonsultasiTeknis extends Model
{
    protected $table = 'konsultasi_teknis';
    protected $guarded = ['id'];

    protected $casts = [
        'assigned_at' => 'datetime',
        'answered_at' => 'datetime',
        'submitted_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function originWilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'origin_wilayah_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedPembina(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_pembina_id');
    }

    /**
     * Guardrail sistem: setiap konsultasi wajib terhubung ke profil pelaku usaha.
     */
    public function profile(): BelongsTo
    {
        return $this->belongsTo(PelakuUsahaProfile::class, 'profile_id');
    }

    public function masterPelaku(): BelongsTo
    {
        return $this->belongsTo(MasterPelakuUsaha::class, 'master_pelaku_id');
    }

    public function interactions(): HasMany
    {
        return $this->hasMany(KonsultasiTeknisInteraction::class, 'konsultasi_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(KonsultasiTeknisAttachment::class, 'konsultasi_id');
    }

    public function history(): HasMany
    {
        return $this->hasMany(KonsultasiTeknisHistory::class, 'konsultasi_id');
    }

    public function followups(): HasMany
    {
        return $this->hasMany(PelakuUsahaFollowup::class, 'konsultasi_teknis_id');
    }
}