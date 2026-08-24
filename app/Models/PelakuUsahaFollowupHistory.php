<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PelakuUsahaFollowupHistory extends Model
{
    protected $table = 'pelaku_usaha_followup_history';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function followup(): BelongsTo
    {
        return $this->belongsTo(PelakuUsahaFollowup::class, 'followup_id');
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }
}