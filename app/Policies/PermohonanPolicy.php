<?php

namespace App\Policies;

use App\Enums\UserRoleType;
use App\Models\Permohonan;
use App\Models\User;

class PermohonanPolicy
{
    /**
     * Admin selalu boleh, jadi tidak perlu diulang di tiap method.
     */
    public function before(User $user, string $ability): ?bool
    {
        return $user->role === UserRoleType::ADMIN->value ? true : null;
    }

    public function viewAny(User $user): bool
    {
        // Pelaku usaha hanya melihat miliknya sendiri (difilter di controller),
        // pembina melihat sesuai wilayah tugasnya.
        return true;
    }

    public function view(User $user, Permohonan $permohonan): bool
    {
        if ($permohonan->user_id === $user->id) {
            return true;
        }

        $role = UserRoleType::tryFrom($user->role);

        if ($role?->isPembina()) {
            return $permohonan->assigned_pembina_id === $user->id
                || $user->wilayah->pluck('id')->contains($permohonan->handling_wilayah_id);
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRoleType::PELAKU_USAHA->value;
    }

    public function update(User $user, Permohonan $permohonan): bool
    {
        // Pemohon hanya boleh edit selagi belum diproses pembina.
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
        $role = UserRoleType::tryFrom($user->role);

        return $role?->isPembina() ?? false;
    }
}