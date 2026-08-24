<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PetugasRegistrationDocument extends Model
{
    protected $table = 'petugas_registration_documents';
    const CREATED_AT = 'uploaded_at';
    const UPDATED_AT = null;
    protected $guarded = ['id'];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(PetugasRegistration::class, 'registration_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}