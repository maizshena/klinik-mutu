<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KonsultasiTeknisHistory extends Model
{
    protected $table = 'konsultasi_teknis_history';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function konsultasi(): BelongsTo
    {
        return $this->belongsTo(KonsultasiTeknis::class, 'konsultasi_id');
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}