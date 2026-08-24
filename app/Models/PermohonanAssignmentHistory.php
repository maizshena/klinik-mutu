<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermohonanAssignmentHistory extends Model
{
    protected $table = 'permohonan_assignment_history';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function permohonan(): BelongsTo
    {
        return $this->belongsTo(Permohonan::class, 'permohonan_id');
    }

    public function fromWilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'from_wilayah_id');
    }

    public function toWilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'to_wilayah_id');
    }

    public function fromPembina(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_pembina_id');
    }

    public function toPembina(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_pembina_id');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}