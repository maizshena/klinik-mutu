<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminAccountInvitation extends Model
{
    protected $table = 'admin_account_invitations';
    public $timestamps = false;
    protected $guarded = ['id'];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function requestedRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'requested_role_id');
    }

    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    public function pembinaMaster(): BelongsTo
    {
        return $this->belongsTo(PembinaMutuMaster::class, 'pembina_master_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function usedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'used_by_user_id');
    }
}