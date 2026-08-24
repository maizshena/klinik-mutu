<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KonsultasiTeknisInteraction extends Model
{
    protected $table = 'konsultasi_teknis_interactions';
    public $timestamps = false;
    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function konsultasi(): BelongsTo
    {
        return $this->belongsTo(KonsultasiTeknis::class, 'konsultasi_id');
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(KonsultasiTeknisAttachment::class, 'interaction_id');
    }
}