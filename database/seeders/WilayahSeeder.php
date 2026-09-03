<?php

namespace Database\Seeders;

use App\Models\Wilayah;
use Illuminate\Database\Seeder;

class WilayahSeeder extends Seeder
{
    /**
     * Data contoh 2 provinsi + kabupaten turunannya, cukup untuk pengujian
     * RBAC berjenjang (nasional → provinsi → kabupaten).
     */
    public function run(): void
    {
        $jabar = Wilayah::updateOrCreate(
            ['kode_wilayah' => '32'],
            ['nama_wilayah' => 'Jawa Barat', 'nama_normalized' => 'jawa barat', 'tipe' => 'provinsi', 'level_wilayah' => 1]
        );

        Wilayah::updateOrCreate(
            ['kode_wilayah' => '3273'],
            [
                'parent_id' => $jabar->id,
                'nama_wilayah' => 'Kota Bandung',
                'nama_normalized' => 'kota bandung',
                'tipe' => 'kabupaten',
                'level_wilayah' => 2,
            ]
        );

        Wilayah::updateOrCreate(
            ['kode_wilayah' => '3216'],
            [
                'parent_id' => $jabar->id,
                'nama_wilayah' => 'Kabupaten Indramayu',
                'nama_normalized' => 'kabupaten indramayu',
                'tipe' => 'kabupaten',
                'level_wilayah' => 2,
            ]
        );

        $jatim = Wilayah::updateOrCreate(
            ['kode_wilayah' => '35'],
            ['nama_wilayah' => 'Jawa Timur', 'nama_normalized' => 'jawa timur', 'tipe' => 'provinsi', 'level_wilayah' => 1]
        );

        Wilayah::updateOrCreate(
            ['kode_wilayah' => '3578'],
            [
                'parent_id' => $jatim->id,
                'nama_wilayah' => 'Kota Surabaya',
                'nama_normalized' => 'kota surabaya',
                'tipe' => 'kabupaten',
                'level_wilayah' => 2,
            ]
        );
    }
}