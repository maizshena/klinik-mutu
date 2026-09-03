<?php

namespace Database\Seeders;

use App\Models\KompetensiMaster;
use Illuminate\Database\Seeder;

class KompetensiMasterSeeder extends Seeder
{
    public function run(): void
    {
        $kompetensi = [
            ['kode_kompetensi' => 'HACCP', 'nama_kompetensi' => 'HACCP (Hazard Analysis Critical Control Point)', 'urutan' => 1],
            ['kode_kompetensi' => 'SANITASI', 'nama_kompetensi' => 'Sanitasi & Higiene Pengolahan', 'urutan' => 2],
            ['kode_kompetensi' => 'LABEL', 'nama_kompetensi' => 'Pelabelan & Kemasan Produk', 'urutan' => 3],
            ['kode_kompetensi' => 'TELUSUR', 'nama_kompetensi' => 'Ketertelusuran (Traceability)', 'urutan' => 4],
            ['kode_kompetensi' => 'SERTIFIKASI', 'nama_kompetensi' => 'Sertifikasi Kelayakan Pengolahan', 'urutan' => 5],
        ];

        foreach ($kompetensi as $item) {
            KompetensiMaster::updateOrCreate(['kode_kompetensi' => $item['kode_kompetensi']], $item);
        }
    }
}