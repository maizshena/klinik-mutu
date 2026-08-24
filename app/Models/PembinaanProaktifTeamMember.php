<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembinaanProaktifTeamMember extends Model
{
    protected $table = 'pembinaan_proaktif_team_members';
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'assigned_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function pembinaan(): BelongsTo
    {
        return $this->belongsTo(PembinaanProaktif::class, 'pembinaan_id');
    }

    public function pembina(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pembina_id');
    }
}