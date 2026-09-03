<?php

namespace App\Policies;

use App\Enums\UserRoleType;
use App\Models\Permohonan;
use App\Models\User;

class PermohonanPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->role === UserRoleType::SUPER_ADMIN->value ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Permohonan $permohonan): bool
    {
        if ($permohonan->user_id === $user->id) {
            return true;
        }

        $role = UserRoleType::tryFrom($user->role);

        if (! $role) {
            return false;
        }

        if (! $role->isScopedByWilayah()) {
            return $role->isAdmin() || $role->isPembina();
        }

        $wilayahDikuasai = $user->wilayah->pluck('id');

        return $permohonan->assigned_pembina_id === $user->id
            || $wilayahDikuasai->contains($permohonan->handling_wilayah_id);
    }

    public function create(User $user): bool
    {
        return UserRoleType::tryFrom($user->role) === UserRoleType::PELAKU_USAHA;
    }

    public function update(User $user, Permohonan $permohonan): bool
    {
        if ($permohonan->user_id === $user->id) {
            return $permohonan->status === 'pending';
        }

        return $permohonan->assigned_pembina_id === $user->id;
    }

    public function delete(User $user, Permohonan $permohonan): bool
    {
        return $permohonan->user_id === $user->id && $permohonan->status === 'pending';
    }

    public function assign(User $user, Permohonan $permohonan): bool
    {
        return UserRoleType::tryFrom($user->role)?->isPembina() ?? false;
    }
}