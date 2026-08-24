<?php

namespace App\Enums;

/**
 * Merepresentasikan nilai kolom legacy `users.role`.
 * Dipakai sebagai acuan cepat di Policy tanpa perlu join ke tabel roles setiap saat.
 */
enum UserRoleType: string
{
    case PELAKU_USAHA = 'pelaku usaha';
    case PEMBINA_PUSAT = 'pembina_pusat';
    case PEMBINA_PROVINSI = 'pembina_provinsi';
    case PEMBINA_KABUPATEN = 'pembina_kabupaten';
    case ADMIN = 'admin';

    public function isPembina(): bool
    {
        return in_array($this, [
            self::PEMBINA_PUSAT,
            self::PEMBINA_PROVINSI,
            self::PEMBINA_KABUPATEN,
        ], true);
    }
}