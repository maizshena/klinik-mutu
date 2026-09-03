<?php

namespace App\Policies;

use App\Enums\UserRoleType;
use App\Models\User;
use App\Models\UserMasterPelakuUsaha;

class UserMasterPelakuUsahaPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->role === UserRoleType::SUPER_ADMIN->value ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return UserRoleType::tryFrom($user->role)?->isAdmin() ?? false;
    }

    /**
     * Hanya admin dengan wilayah yang cocok yang boleh memverifikasi klaim —
     * guardrail RBAC wilayah.
     */
    public function verify(User $user, UserMasterPelakuUsaha $claim): bool
    {
        $role = UserRoleType::tryFrom($user->role);

        if (! $role?->isAdmin()) {
            return false;
        }

        if (! $role->isScopedByWilayah()) {
            return true; // admin_pusat / super_admin: nasional.
        }

        return $user->wilayah->pluck('id')->contains($claim->handling_wilayah_id);
    }
}