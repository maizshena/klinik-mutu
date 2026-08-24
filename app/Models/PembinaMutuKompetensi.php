<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembinaMutuKompetensi extends Model
{
    protected $table = 'pembina_mutu_kompetensi';
    protected $guarded = ['id'];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function pembina(): BelongsTo
    {
        return $this->belongsTo(PembinaMutuMaster::class, 'pembina_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kompetensi(): BelongsTo
    {
        return $this->belongsTo(KompetensiMaster::class, 'kompetensi_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}