<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMasterPelakuUsahaHistory extends Model
{
    protected $table = 'user_master_pelaku_usaha_history';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function claim(): BelongsTo
    {
        return $this->belongsTo(UserMasterPelakuUsaha::class, 'claim_id');
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }
}