<?php

namespace App\Http\Controllers;

use App\Models\KonsultasiTeknisAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SecureFileController extends Controller
{
    /**
     * Menyajikan file lampiran konsultasi teknis hanya untuk user yang berhak
     * (pemohon pemilik atau pembina yang ditugaskan) — guardrail keamanan file.
     */
    public function konsultasiAttachment(Request $request, KonsultasiTeknisAttachment $attachment): StreamedResponse
    {
        $this->authorize('view', $attachment->konsultasi);

        abort_unless(
            Storage::disk('private')->exists("konsultasi-teknis/{$attachment->stored_name}"),
            404
        );

        return Storage::disk('private')->response(
            "konsultasi-teknis/{$attachment->stored_name}",
            $attachment->original_name
        );
    }
}