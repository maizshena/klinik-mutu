<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PelakuUsahaImportStaging extends Model
{
    protected $table = 'pelaku_usaha_import_staging';
    public $timestamps = false;
    protected $guarded = ['id'];

    protected $casts = [
        'payload_json' => 'array',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(PelakuUsahaImportBatch::class, 'batch_id');
    }

    public function master(): BelongsTo
    {
        return $this->belongsTo(MasterPelakuUsaha::class, 'master_id');
    }
}