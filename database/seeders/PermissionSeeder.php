<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['nama_permission' => 'permohonan.view', 'kategori' => 'permohonan'],
            ['nama_permission' => 'permohonan.create', 'kategori' => 'permohonan'],
            ['nama_permission' => 'permohonan.assign', 'kategori' => 'permohonan'],
            ['nama_permission' => 'konsultasi.view', 'kategori' => 'konsultasi_teknis'],
            ['nama_permission' => 'konsultasi.create', 'kategori' => 'konsultasi_teknis'],
            ['nama_permission' => 'konsultasi.answer', 'kategori' => 'konsultasi_teknis'],
            ['nama_permission' => 'pembinaan.view', 'kategori' => 'pembinaan_proaktif'],
            ['nama_permission' => 'pembinaan.create', 'kategori' => 'pembinaan_proaktif'],
            ['nama_permission' => 'pembinaan.assign', 'kategori' => 'pembinaan_proaktif'],
            ['nama_permission' => 'pembinaan.complete', 'kategori' => 'pembinaan_proaktif'],
            ['nama_permission' => 'followup.view', 'kategori' => 'tindak_lanjut'],
            ['nama_permission' => 'followup.create', 'kategori' => 'tindak_lanjut'],
            ['nama_permission' => 'followup.verify', 'kategori' => 'tindak_lanjut'],
            ['nama_permission' => 'kusuka.claim.verify', 'kategori' => 'kusuka'],
            ['nama_permission' => 'knowledge.publish', 'kategori' => 'knowledge_base'],
            ['nama_permission' => 'user.manage', 'kategori' => 'admin'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['nama_permission' => $permission['nama_permission']], $permission);
        }

        // Mapping default: admin & pembina pusat dapat semua permission.
        $allPermissionIds = Permission::pluck('id');

        foreach (['super_admin', 'admin_pusat', 'pembina_pusat'] as $roleName) {
            $role = Role::where('nama_role', $roleName)->first();
            $role?->permissions()->sync($allPermissionIds);
        }

        // admin_provinsi & admin_kabupaten: semua kecuali kelola user global.
        $scopedAdminPermissions = Permission::where('nama_permission', '!=', 'user.manage')->pluck('id');
        foreach (['admin_provinsi', 'admin_kabupaten'] as $roleName) {
            $role = Role::where('nama_role', $roleName)->first();
            $role?->permissions()->sync($scopedAdminPermissions);
        }

        // pembina_daerah: hanya operasional, tidak bisa verifikasi klaim KUSUKA.
        $pembinaPermissions = Permission::whereIn('nama_permission', [
            'permohonan.view', 'permohonan.assign',
            'konsultasi.view', 'konsultasi.answer',
            'pembinaan.view', 'pembinaan.complete',
            'followup.view', 'followup.verify',
        ])->pluck('id');
        Role::where('nama_role', 'pembina_daerah')->first()?->permissions()->sync($pembinaPermissions);

        // pelaku_usaha: hanya create & view miliknya (filter user_id ditangani di controller).
        $pelakuPermissions = Permission::whereIn('nama_permission', [
            'permohonan.view', 'permohonan.create',
            'konsultasi.view', 'konsultasi.create',
            'followup.view',
        ])->pluck('id');
        Role::where('nama_role', 'pelaku_usaha')->first()?->permissions()->sync($pelakuPermissions);
    }
}