<?php

namespace App\Http\Controllers;

use App\Models\MasterPelakuUsaha;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KusukaSearchController extends Controller
{
    /**
     * Endpoint pencarian KUSUKA untuk form registrasi (dipanggil via fetch/axios dari React).
     * Guardrail: jangan pernah membuat duplikasi master, jadi ini murni read-only.
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'no_kusuka' => ['required', 'string', 'min:3'],
        ]);

        $pelaku = MasterPelakuUsaha::query()
            ->where('no_kusuka', $request->no_kusuka)
            ->select(['id', 'no_kusuka', 'nama_pelaku', 'nama_usaha', 'provinsi_name', 'kabupaten_name', 'kecamatan_name'])
            ->first();

        if (! $pelaku) {
            return response()->json([
                'found' => false,
                'message' => 'Nomor KUSUKA tidak ditemukan. Silakan gunakan jalur pendaftaran manual.',
            ], 404);
        }

        // Cek apakah KUSUKA ini sudah pernah diklaim & terverifikasi oleh user lain.
        $sudahTerhubung = $pelaku->userClaims()->where('link_status', 'terverifikasi')->exists();

        return response()->json([
            'found' => true,
            'sudah_terhubung' => $sudahTerhubung,
            'data' => $pelaku,
        ]);
    }
}