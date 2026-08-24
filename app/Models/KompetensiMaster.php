<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KompetensiMaster extends Model
{
    protected $table = 'kompetensi_master';
    protected $guarded = ['id'];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function pembinaMutuKompetensi(): HasMany
    {
        return $this->hasMany(PembinaMutuKompetensi::class, 'kompetensi_id');
    }

    public function petugasRegistrationKompetensi(): HasMany
    {
        return $this->hasMany(PetugasRegistrationKompetensi::class, 'kompetensi_id');
    }
}