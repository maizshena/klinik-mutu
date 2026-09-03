<?php

namespace App\Policies;

use App\Enums\UserRoleType;
use App\Models\PembinaanProaktif;
use App\Models\User;

class PembinaanProaktifPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->role === UserRoleType::SUPER_ADMIN->value ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, PembinaanProaktif $pembinaan): bool
    {
        $role = UserRoleType::tryFrom($user->role);

        if (! $role) {
            return false;
        }

        if (! $role->isScopedByWilayah()) {
            return true;
        }

        $wilayahDikuasai = $user->wilayah->pluck('id');

        return $pembinaan->assigned_pembina_id === $user->id
            || $wilayahDikuasai->contains($pembinaan->target_district_id)
            || $wilayahDikuasai->contains($pembinaan->target_province_id)
            || $pembinaan->teamMembers()->where('pembina_id', $user->id)->exists();
    }

    /**
     * Hanya admin yang boleh membuat penugasan pembinaan proaktif.
     */
    public function create(User $user): bool
    {
        return UserRoleType::tryFrom($user->role)?->isAdmin() ?? false;
    }

    public function assign(User $user, PembinaanProaktif $pembinaan): bool
    {
        return UserRoleType::tryFrom($user->role)?->isAdmin() ?? false;
    }

    /**
     * Pembina yang ditugaskan boleh mengisi temuan & rekomendasi.
     */
    public function submitFindings(User $user, PembinaanProaktif $pembinaan): bool
    {
        return $pembinaan->assigned_pembina_id === $user->id
            && $pembinaan->status === 'sedang_dilaksanakan';
    }

    /**
     * Persetujuan akhir tetap di tangan admin (bukan pembina sendiri).
     */
    public function complete(User $user, PembinaanProaktif $pembinaan): bool
    {
        return UserRoleType::tryFrom($user->role)?->isAdmin() ?? false;
    }

    public function cancel(User $user, PembinaanProaktif $pembinaan): bool
    {
        return UserRoleType::tryFrom($user->role)?->isAdmin() ?? false;
    }
}