<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalHistory extends Model
{
    protected $table = 'approval_history';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function permohonan(): BelongsTo
    {
        return $this->belongsTo(Permohonan::class, 'permohonan_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}