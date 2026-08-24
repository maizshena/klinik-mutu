<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembinaMutuNomor extends Model
{
    protected $table = 'pembina_mutu_nomor';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function pembina(): BelongsTo
    {
        return $this->belongsTo(PembinaMutuMaster::class, 'pembina_id');
    }
}