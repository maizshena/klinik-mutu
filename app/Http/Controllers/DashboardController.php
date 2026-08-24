<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $user = $request->user();
        $role = UserRoleType::tryFrom($user->role);

        $stats = $role?->isPembina()
            ? [
                'permohonan_ditangani' => $user->permohonanDitangani()->count(),
                'konsultasi_ditangani' => $user->konsultasiTeknisDitangani()->count(),
                'permohonan_pending' => $user->permohonanDitangani()
                    ->where('status', 'pending')->count(),
            ]
            : [
                'permohonan_saya' => $user->permohonan()->count(),
                'konsultasi_saya' => $user->konsultasiTeknisDibuat()->count(),
            ];

        return Inertia::render('Dashboard', [
            'stats' => $stats,
        ]);
    }
}