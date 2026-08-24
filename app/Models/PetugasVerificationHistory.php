<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PetugasVerificationHistory extends Model
{
    protected $table = 'petugas_verification_history';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(PetugasRegistration::class, 'registration_id');
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'action_by');
    }
}