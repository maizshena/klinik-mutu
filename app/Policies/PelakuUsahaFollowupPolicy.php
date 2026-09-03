<?php

namespace App\Policies;

use App\Enums\UserRoleType;
use App\Models\PelakuUsahaFollowup;
use App\Models\User;

class PelakuUsahaFollowupPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->role === UserRoleType::SUPER_ADMIN->value ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, PelakuUsahaFollowup $followup): bool
    {
        if ($followup->profile->user_id === $user->id) {
            return true;
        }

        $role = UserRoleType::tryFrom($user->role);

        return $followup->assigned_pembina_id === $user->id
            || ($role?->isAdmin() && (
                ! $role->isScopedByWilayah()
                || $user->wilayah->pluck('id')->contains($followup->profile->kabupaten_id)
            ));
    }

    /**
     * Rencana tindak lanjut dibuat oleh pembina/admin, bukan pelaku usaha sendiri.
     */
    public function create(User $user): bool
    {
        $role = UserRoleType::tryFrom($user->role);

        return ($role?->isAdmin() || $role?->isPembina()) ?? false;
    }

    /**
     * Pelaku usaha yang jadi penanggung jawab boleh update progres perbaikan.
     */
    public function updateProgress(User $user, PelakuUsahaFollowup $followup): bool
    {
        return $followup->profile->user_id === $user->id
            && $followup->workflow_status !== 'selesai';
    }

    public function uploadEvidence(User $user, PelakuUsahaFollowup $followup): bool
    {
        return $this->updateProgress($user, $followup);
    }

    /**
     * Verifikasi bukti perbaikan hanya oleh pembina yang ditugaskan.
     */
    public function verify(User $user, PelakuUsahaFollowup $followup): bool
    {
        return $followup->assigned_pembina_id === $user->id;
    }
}