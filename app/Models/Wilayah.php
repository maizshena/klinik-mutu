<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wilayah extends Model
{
    protected $table = 'wilayah';
    public $timestamps = false;
    protected $guarded = ['id'];

    protected $casts = [
        'aktif' => 'boolean',
        'created_at' => 'datetime',
        'layanan_updated_at' => 'datetime',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Wilayah::class, 'parent_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_wilayah', 'wilayah_id', 'user_id')
            ->withPivot(['is_primary', 'access_type', 'assigned_at']);
    }

    public function pembinaMutu(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(PembinaMutuMaster::class, 'pembina_mutu_wilayah', 'wilayah_id', 'pembina_id')
            ->withPivot(['is_primary', 'source_text']);
    }
}