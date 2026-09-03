<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $jabar = Wilayah::where('kode_wilayah', '32')->first();
        $bandung = Wilayah::where('kode_wilayah', '3273')->first();

        $accounts = [
            ['role' => 'super_admin', 'nama' => 'Super Admin', 'email' => 'superadmin@klinikmutu.id'],
            ['role' => 'admin_pusat', 'nama' => 'Admin Pusat', 'email' => 'adminpusat@klinikmutu.id'],
            ['role' => 'admin_provinsi', 'nama' => 'Admin Provinsi Jabar', 'email' => 'admin.jabar@klinikmutu.id', 'wilayah' => $jabar],
            ['role' => 'admin_kabupaten', 'nama' => 'Admin Kota Bandung', 'email' => 'admin.bandung@klinikmutu.id', 'wilayah' => $bandung],
            ['role' => 'pembina_pusat', 'nama' => 'Pembina Pusat', 'email' => 'pembina.pusat@klinikmutu.id'],
            ['role' => 'pembina_daerah', 'nama' => 'Pembina Kota Bandung', 'email' => 'pembina.bandung@klinikmutu.id', 'wilayah' => $bandung],
            ['role' => 'pelaku_usaha', 'nama' => 'Budi Santoso', 'email' => 'budi@contoh.id'],
        ];

        foreach ($accounts as $account) {
            $roleModel = Role::where('nama_role', $account['role'])->first();

            $user = User::updateOrCreate(
                ['email' => $account['email']],
                [
                    'nama_lengkap' => $account['nama'],
                    'profesi' => str_contains($account['role'], 'admin') || str_contains($account['role'], 'pembina')
                        ? 'ASN/Petugas'
                        : 'Pelaku Usaha',
                    'nama_usaha' => $account['role'] === 'pelaku_usaha' ? 'UD Berkah Laut' : '-',
                    'role_id' => $roleModel?->id,
                    'whatsapp' => '08123456789',
                    'password_hash' => Hash::make('password123'),
                    'role' => $account['role'],
                    'account_status' => 'aktif',
                ]
            );

            if (! empty($account['wilayah'])) {
                $user->wilayah()->syncWithoutDetaching([
                    $account['wilayah']->id => ['is_primary' => true, 'access_type' => 'utama'],
                ]);
            }
        }
    }
}