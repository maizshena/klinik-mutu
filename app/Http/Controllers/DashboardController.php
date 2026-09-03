<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleType;
use App\Http\Controllers\Concerns\ScopesByWilayah;
use App\Models\MasterPelakuUsaha;
use App\Models\PembinaanProaktif;
use App\Models\Permohonan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    use ScopesByWilayah;

    public function __invoke(Request $request): Response
    {
        $user = $request->user();
        $role = UserRoleType::tryFrom($user->role);
        $isPelakuUsaha = $role === UserRoleType::PELAKU_USAHA;

        if ($isPelakuUsaha) {
            return Inertia::render('Dashboard', [
                'isPelakuUsaha' => true,
                'stats' => $this->pelakuUsahaStats($user),
                'recentActivity' => $this->pelakuUsahaActivity($user),
            ]);
        }

        $scopedPermohonan = $this->scopedPermohonanQuery($user, $role);
        $scopedPembinaan = $this->scopedPembinaanQuery($user, $role);
        $scopedMaster = $this->scopedMasterPelakuQuery($user, $role);

        return Inertia::render('Dashboard', [
            'isPelakuUsaha' => false,
            'stats' => $this->petugasStats($scopedMaster, $scopedPembinaan),
            'alerts' => $this->pendingAlerts($scopedPermohonan),
            'monthlyTrend' => $this->monthlyTrend($scopedPermohonan),
            'recentActivity' => $this->combinedActivity($scopedPermohonan, $scopedPembinaan),
        ]);
    }

    private function scopedPermohonanQuery(User $user, UserRoleType $role)
    {
        $query = Permohonan::query();

        if ($role->isPembina()) {
            return $query->where('assigned_pembina_id', $user->id);
        }

        return $this->applyWilayahScope($query, $user, 'handling_wilayah_id');
    }

    private function scopedPembinaanQuery(User $user, UserRoleType $role)
    {
        $query = PembinaanProaktif::query();

        if ($role->isPembina()) {
            return $query->where('assigned_pembina_id', $user->id);
        }

        return $this->applyWilayahScope($query, $user, 'target_district_id');
    }

    private function scopedMasterPelakuQuery(User $user, UserRoleType $role)
    {
        $query = MasterPelakuUsaha::query();

        // pembina roles don't own a wilayah column on master_pelaku_usaha directly,
        // so pembina_daerah falls back to their assigned wilayah scope like admins do
        return $this->applyWilayahScope($query, $user, 'kabupaten_wilayah_id');
    }

    private function petugasStats($scopedMaster, $scopedPembinaan): array
    {
        $totalPelakuUsaha = (clone $scopedMaster)->count();
        $totalPembinaan = (clone $scopedPembinaan)->count();
        $pembinaanBerjalan = (clone $scopedPembinaan)->whereNotIn('status', ['selesai', 'dibatalkan'])->count();
        $pembinaanAktif = (clone $scopedPembinaan)->where('status', 'sedang_dilaksanakan')->count();
        $pembinaanSelesai = (clone $scopedPembinaan)->where('status', 'selesai')->count();

        // placeholder proxy metric — no dedicated mutu-scoring model exists yet in the
        // schema, so this approximates "score mutu" as the completion rate of pembinaan.
        // replace with a real scoring model once one is defined.
        $scoreMutu = $totalPembinaan > 0 ? round(($pembinaanSelesai / $totalPembinaan) * 100) : 0;

        return [
            ['key' => 'pelaku_usaha', 'label' => 'Total Pelaku Usaha', 'value' => $totalPelakuUsaha, 'suffix' => ''],
            ['key' => 'status_pembinaan', 'label' => 'Status Pembinaan', 'value' => $pembinaanBerjalan, 'suffix' => ' berjalan'],
            ['key' => 'score_mutu', 'label' => 'Score Mutu', 'value' => $scoreMutu, 'suffix' => '%'],
            ['key' => 'pembinaan_aktif', 'label' => 'Pembinaan Aktif', 'value' => $pembinaanAktif, 'suffix' => ''],
        ];
    }

    // right column of the middle bento row: items needing the officer's attention
    private function pendingAlerts($scopedPermohonan): array
    {
        return (clone $scopedPermohonan)
            ->where('status', 'pending')
            ->oldest()
            ->limit(5)
            ->get(['id', 'nama_pemohon', 'jenis_layanan', 'created_at'])
            ->map(fn ($item) => [
                'id' => $item->id,
                'title' => $item->nama_pemohon,
                'subtitle' => $item->jenis_layanan,
                'waitingSince' => $item->created_at->diffForHumans(),
                'daysWaiting' => $item->created_at->diffInDays(now()),
            ])->toArray();
    }

    private function monthlyTrend($scopedPermohonan): array
    {
        $months = collect(range(5, 0))->map(fn ($i) => now()->subMonths($i));

        return $months->map(function (Carbon $month) use ($scopedPermohonan) {
            $submitted = (clone $scopedPermohonan)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $completed = (clone $scopedPermohonan)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->where('status', 'selesai')
                ->count();

            return ['label' => $month->translatedFormat('M'), 'submitted' => $submitted, 'completed' => $completed];
        })->toArray();
    }

    // bottom row: merges permohonan + pembinaan into one activity strip, newest first
    private function combinedActivity($scopedPermohonan, $scopedPembinaan): array
    {
        $permohonanItems = (clone $scopedPermohonan)
            ->latest()
            ->limit(5)
            ->get(['id', 'nama_pemohon', 'jenis_layanan', 'status', 'updated_at'])
            ->map(fn ($item) => [
                'type' => 'permohonan',
                'id' => $item->id,
                'title' => $item->nama_pemohon,
                'subtitle' => $item->jenis_layanan,
                'status' => $item->status,
                'timestamp' => $item->updated_at,
            ]);

        $pembinaanItems = (clone $scopedPembinaan)
            ->latest()
            ->limit(5)
            ->get(['id', 'nomor_pembinaan', 'directive_type', 'status', 'updated_at'])
            ->map(fn ($item) => [
                'type' => 'pembinaan',
                'id' => $item->id,
                'title' => $item->nomor_pembinaan ?? 'Pembinaan',
                'subtitle' => $item->directive_type,
                'status' => $item->status,
                'timestamp' => $item->updated_at,
            ]);

        return $permohonanItems->merge($pembinaanItems)
            ->sortByDesc('timestamp')
            ->take(6)
            ->map(fn ($item) => [
                ...$item,
                'date' => $item['timestamp']->diffForHumans(),
            ])
            ->values()
            ->toArray();
    }

    private function pelakuUsahaStats(User $user): array
    {
        return [
            ['key' => 'permohonan', 'label' => 'Permohonan Saya', 'value' => $user->permohonan()->count(), 'suffix' => ''],
            ['key' => 'konsultasi', 'label' => 'Konsultasi Saya', 'value' => $user->konsultasiTeknisDibuat()->count(), 'suffix' => ''],
            ['key' => 'pending', 'label' => 'Menunggu Diproses', 'value' => $user->permohonan()->where('status', 'pending')->count(), 'suffix' => ''],
            ['key' => 'selesai', 'label' => 'Konsultasi Selesai', 'value' => $user->konsultasiTeknisDibuat()->where('status', 'selesai')->count(), 'suffix' => ''],
        ];
    }

    private function pelakuUsahaActivity(User $user): array
    {
        return $user->permohonan()
            ->latest()
            ->limit(6)
            ->get(['id', 'nomor_tiket', 'jenis_layanan', 'status', 'created_at'])
            ->map(fn ($item) => [
                'id' => $item->id,
                'title' => $item->jenis_layanan,
                'subtitle' => $item->nomor_tiket,
                'status' => $item->status,
                'date' => $item->created_at->diffForHumans(),
            ])->toArray();
    }
}