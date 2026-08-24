<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermohonanAttachment extends Model
{
    protected $table = 'permohonan_attachments';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function permohonan(): BelongsTo
    {
        return $this->belongsTo(Permohonan::class, 'permohonan_id');
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}