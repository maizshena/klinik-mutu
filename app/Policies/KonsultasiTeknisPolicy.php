<?php

namespace App\Policies;

use App\Enums\UserRoleType;
use App\Models\KonsultasiTeknis;
use App\Models\User;

class KonsultasiTeknisPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->role === UserRoleType::SUPER_ADMIN->value ? true : null;
    }

    public function viewAny(User $user): bool
    {
        // Filter aktual (by user_id atau by wilayah) dilakukan di Controller
        // via trait ScopesByWilayah — policy ini hanya gerbang akses fitur.
        return true;
    }

    public function view(User $user, KonsultasiTeknis $konsultasi): bool
    {
        if ($konsultasi->created_by === $user->id) {
            return true;
        }

        $role = UserRoleType::tryFrom($user->role);

        if (! $role) {
            return false;
        }

        // admin_pusat & pembina_pusat: akses nasional.
        if (! $role->isScopedByWilayah()) {
            return $role->isAdmin() || $role->isPembina();
        }

        // admin_provinsi / admin_kabupaten / pembina_daerah: wajib match wilayah asal konsultasi.
        $wilayahDikuasai = $user->wilayah->pluck('id');

        return $konsultasi->assigned_pembina_id === $user->id
            || $wilayahDikuasai->contains($konsultasi->origin_wilayah_id);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, KonsultasiTeknis $konsultasi): bool
    {
        return $konsultasi->created_by === $user->id
            && in_array($konsultasi->status, ['draft', 'perlu_perbaikan'], true);
    }

    public function delete(User $user, KonsultasiTeknis $konsultasi): bool
    {
        return $konsultasi->created_by === $user->id && $konsultasi->status === 'draft';
    }

    public function answer(User $user, KonsultasiTeknis $konsultasi): bool
    {
        $role = UserRoleType::tryFrom($user->role);

        return $role?->isPembina() && $konsultasi->assigned_pembina_id === $user->id;
    }
}