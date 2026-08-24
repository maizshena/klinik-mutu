<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permohonan extends Model
{
    protected $table = 'permohonan';
    protected $guarded = ['id'];

    protected $casts = [
        'assigned_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    public function handlingWilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'handling_wilayah_id');
    }

    public function assignedPembina(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_pembina_id');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function assignmentHistory(): HasMany
    {
        return $this->hasMany(PermohonanAssignmentHistory::class, 'permohonan_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(PermohonanAttachment::class, 'permohonan_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(PermohonanMessage::class, 'permohonan_id');
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(PermohonanStatusHistory::class, 'permohonan_id');
    }

    public function approvalHistory(): HasMany
    {
        return $this->hasMany(ApprovalHistory::class, 'permohonan_id');
    }

    public function survei(): HasMany
    {
        return $this->hasMany(SurveiKepuasan::class, 'permohonan_id');
    }

    public function followups(): HasMany
    {
        return $this->hasMany(PelakuUsahaFollowup::class, 'permohonan_id');
    }
}