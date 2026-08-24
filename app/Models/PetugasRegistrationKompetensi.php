<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PetugasRegistrationKompetensi extends Pivot
{
    protected $table = 'petugas_registration_kompetensi';
    public $timestamps = false;
    protected $guarded = [];
    public $incrementing = false;
}