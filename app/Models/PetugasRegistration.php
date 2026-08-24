<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PetugasRegistration extends Model
{
    protected $table = 'petugas_registrations';
    const CREATED_AT = 'submitted_at';
    const UPDATED_AT = 'updated_at';
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_surat_tugas' => 'date',
        'masa_tugas_mulai' => 'date',
        'masa_tugas_akhir' => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function requestedRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'requested_role_id');
    }

    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    public function adminInvitation(): BelongsTo
    {
        return $this->belongsTo(AdminAccountInvitation::class, 'admin_invitation_id');
    }

    public function pembinaMaster(): BelongsTo
    {
        return $this->belongsTo(PembinaMutuMaster::class, 'pembina_master_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(PetugasRegistrationDocument::class, 'registration_id');
    }

    public function kompetensi(): BelongsToMany
    {
        return $this->belongsToMany(KompetensiMaster::class, 'petugas_registration_kompetensi', 'registration_id', 'kompetensi_id')
            ->withPivot(['kompetensi_lainnya']);
    }

    public function verificationHistory(): HasMany
    {
        return $this->hasMany(PetugasVerificationHistory::class, 'registration_id');
    }
}