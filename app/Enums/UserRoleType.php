<?php

namespace App\Enums;

/**
 * Merepresentasikan kolom legacy `users.role`.
 * Disesuaikan dengan RBAC resmi: super_admin, admin_pusat, admin_provinsi,
 * admin_kabupaten, pembina_pusat, pembina_daerah, pelaku_usaha.
 */
enum UserRoleType: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN_PUSAT = 'admin_pusat';
    case ADMIN_PROVINSI = 'admin_provinsi';
    case ADMIN_KABUPATEN = 'admin_kabupaten';
    case PEMBINA_PUSAT = 'pembina_pusat';
    case PEMBINA_DAERAH = 'pembina_daerah';
    case PELAKU_USAHA = 'pelaku_usaha';

    public function isPembina(): bool
    {
        return in_array($this, [self::PEMBINA_PUSAT, self::PEMBINA_DAERAH], true);
    }

    public function isAdmin(): bool
    {
        return in_array($this, [
            self::SUPER_ADMIN, self::ADMIN_PUSAT, self::ADMIN_PROVINSI, self::ADMIN_KABUPATEN,
        ], true);
    }

    /**
     * Level akses wilayah: 0 = nasional (tidak dibatasi), 1 = provinsi, 2 = kabupaten.
     * Dipakai untuk menentukan seberapa sempit filter wilayah yang diterapkan.
     */
    public function wilayahScopeLevel(): int
    {
        return match ($this) {
            self::SUPER_ADMIN, self::ADMIN_PUSAT, self::PEMBINA_PUSAT => 0,
            self::ADMIN_PROVINSI => 1,
            self::ADMIN_KABUPATEN, self::PEMBINA_DAERAH => 2,
            self::PELAKU_USAHA => 0, // tidak relevan, difilter by user_id bukan wilayah
        };
    }

    /**
     * Apakah role ini harus difilter ketat berdasarkan wilayah yang ditugaskan.
     */
    public function isScopedByWilayah(): bool
    {
        return $this->wilayahScopeLevel() > 0 || $this === self::PEMBINA_DAERAH;
    }
}