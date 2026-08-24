<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembinaanProaktifAttachment extends Model
{
    protected $table = 'pembinaan_proaktif_attachments';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function pembinaan(): BelongsTo
    {
        return $this->belongsTo(PembinaanProaktif::class, 'pembinaan_id');
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}