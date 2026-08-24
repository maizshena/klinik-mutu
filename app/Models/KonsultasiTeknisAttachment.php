<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KonsultasiTeknisAttachment extends Model
{
    protected $table = 'konsultasi_teknis_attachments';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function konsultasi(): BelongsTo
    {
        return $this->belongsTo(KonsultasiTeknis::class, 'konsultasi_id');
    }

    public function interaction(): BelongsTo
    {
        return $this->belongsTo(KonsultasiTeknisInteraction::class, 'interaction_id');
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}