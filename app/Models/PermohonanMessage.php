<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermohonanMessage extends Model
{
    protected $table = 'permohonan_messages';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function permohonan(): BelongsTo
    {
        return $this->belongsTo(Permohonan::class, 'permohonan_id');
    }
}