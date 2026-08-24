<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PelakuUsahaImportBatch extends Model
{
    protected $table = 'pelaku_usaha_import_batches';
    const CREATED_AT = 'started_at';
    const UPDATED_AT = null;
    protected $guarded = ['id'];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function startedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'started_by');
    }

    public function stagingRows(): HasMany
    {
        return $this->hasMany(PelakuUsahaImportStaging::class, 'batch_id');
    }

    public function masterPelakuUsaha(): HasMany
    {
        return $this->hasMany(MasterPelakuUsaha::class, 'imported_batch_id');
    }
}