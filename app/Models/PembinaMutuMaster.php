<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PembinaMutuMaster extends Model
{
    protected $table = 'pembina_mutu_master';
    protected $guarded = ['id'];

    protected $casts = [
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    public function linkedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'linked_user_id');
    }

    public function nomor(): HasMany
    {
        return $this->hasMany(PembinaMutuNomor::class, 'pembina_id');
    }

    public function wilayah(): BelongsToMany
    {
        return $this->belongsToMany(Wilayah::class, 'pembina_mutu_wilayah', 'pembina_id', 'wilayah_id')
            ->withPivot(['is_primary', 'source_text']);
    }

    public function kompetensi(): HasMany
    {
        return $this->hasMany(PembinaMutuKompetensi::class, 'pembina_id');
    }

    public function adminInvitations(): HasMany
    {
        return $this->hasMany(AdminAccountInvitation::class, 'pembina_master_id');
    }

    public function petugasRegistrations(): HasMany
    {
        return $this->hasMany(PetugasRegistration::class, 'pembina_master_id');
    }
}