<?php

namespace App\Http\Controllers\Concerns;

use App\Enums\UserRoleType;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * Menerapkan guardrail: "Semua akses data wajib dibatasi berdasarkan wilayah user."
 * Dipakai oleh Controller yang query-nya harus tunduk pada RBAC wilayah
 * (Permohonan, Konsultasi Teknis, Pembinaan Proaktif, Master Pelaku Usaha, dll).
 */
trait ScopesByWilayah
{
    /**
     * @param  Builder  $query  Query dasar (mis. Permohonan::query())
     * @param  User  $user
     * @param  string  $wilayahColumn  Nama kolom wilayah di tabel target (mis. 'handling_wilayah_id')
     */
    protected function applyWilayahScope(Builder $query, User $user, string $wilayahColumn): Builder
    {
        $role = UserRoleType::tryFrom($user->role);

        // Pusat & super admin: akses nasional, tidak difilter.
        if (! $role || ! $role->isScopedByWilayah()) {
            return $query;
        }

        // Provinsi/kabupaten/pembina daerah: hanya wilayah yang ditugaskan ke user ini.
        $wilayahIds = $user->wilayah()->pluck('wilayah.id');

        return $query->whereIn($wilayahColumn, $wilayahIds);
    }
}