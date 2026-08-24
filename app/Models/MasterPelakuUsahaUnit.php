<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MasterPelakuUsahaUnit extends Model
{
    protected $table = 'master_pelaku_usaha_units';
    const CREATED_AT = 'imported_at';
    const UPDATED_AT = 'updated_at';
    protected $guarded = ['id'];

    protected $casts = [
        'omzet_tahunan' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function masterPelaku(): BelongsTo
    {
        return $this->belongsTo(MasterPelakuUsaha::class, 'master_pelaku_id');
    }

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'provinsi_wilayah_id');
    }

    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'kabupaten_wilayah_id');
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'kecamatan_wilayah_id');
    }

    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'kelurahan_wilayah_id');
    }

    public function importBatch(): BelongsTo
    {
        return $this->belongsTo(PelakuUsahaImportBatch::class, 'imported_batch_id');
    }
}