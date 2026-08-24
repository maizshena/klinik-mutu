<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembinaanProaktifHistory extends Model
{
    protected $table = 'pembinaan_proaktif_history';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function pembinaan(): BelongsTo
    {
        return $this->belongsTo(PembinaanProaktif::class, 'pembinaan_id');
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}