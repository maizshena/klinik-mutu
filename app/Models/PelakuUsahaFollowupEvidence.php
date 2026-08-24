<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PelakuUsahaFollowupEvidence extends Model
{
    protected $table = 'pelaku_usaha_followup_evidence';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function followup(): BelongsTo
    {
        return $this->belongsTo(PelakuUsahaFollowup::class, 'followup_id');
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}