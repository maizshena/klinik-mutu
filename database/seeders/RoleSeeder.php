<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * 7 role resmi sesuai dokumen RBAC sistem lama.
     */
    public function run(): void
    {
        $roles = [
            ['nama_role' => 'super_admin', 'level' => 1, 'keterangan' => 'Akses penuh seluruh sistem, tanpa batasan wilayah.'],
            ['nama_role' => 'admin_pusat', 'level' => 2, 'keterangan' => 'Admin tingkat pusat, akses nasional.'],
            ['nama_role' => 'admin_provinsi', 'level' => 3, 'keterangan' => 'Admin tingkat provinsi, dibatasi wilayah provinsi.'],
            ['nama_role' => 'admin_kabupaten', 'level' => 4, 'keterangan' => 'Admin tingkat kabupaten/kota, dibatasi wilayah kabupaten.'],
            ['nama_role' => 'pembina_pusat', 'level' => 3, 'keterangan' => 'Pembina Mutu tingkat pusat, akses nasional.'],
            ['nama_role' => 'pembina_daerah', 'level' => 4, 'keterangan' => 'Pembina Mutu tingkat provinsi/kabupaten, dibatasi wilayah tugas.'],
            ['nama_role' => 'pelaku_usaha', 'level' => 5, 'keterangan' => 'Pengguna layanan (pelaku usaha perikanan).'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['nama_role' => $role['nama_role']], $role);
        }
    }
}