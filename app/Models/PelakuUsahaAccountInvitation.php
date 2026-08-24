<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PelakuUsahaAccountInvitation extends Model
{
    protected $table = 'pelaku_usaha_account_invitations';
    public $timestamps = false;
    protected $guarded = ['id'];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(PelakuUsahaProfile::class, 'profile_id');
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}