<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleType;
use App\Http\Controllers\Concerns\ScopesByWilayah;
use App\Http\Requests\Kusuka\VerifyKusukaClaimRequest;
use App\Models\UserMasterPelakuUsaha;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class KusukaClaimController extends Controller
{
    use ScopesByWilayah;

    /**
     * Daftar klaim KUSUKA yang menunggu verifikasi, otomatis difilter
     * berdasarkan wilayah admin yang login (guardrail RBAC wilayah).
     */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', UserMasterPelakuUsaha::class);

        $user = $request->user();

        $query = UserMasterPelakuUsaha::query()
            ->with(['user:id,nama_lengkap,email', 'masterPelaku:id,no_kusuka,nama_pelaku,nama_usaha'])
            ->where('link_status', 'menunggu_verifikasi');

        $query = $this->applyWilayahScope($query, $user, 'handling_wilayah_id');

        $claims = $query->latest()->paginate(15);

        return Inertia::render('Kusuka/ClaimIndex', [
            'claims' => $claims,
        ]);
    }

    public function verify(VerifyKusukaClaimRequest $request, UserMasterPelakuUsaha $claim): RedirectResponse
    {
        DB::transaction(function () use ($request, $claim) {
            $disetujui = $request->decision === 'setujui';

            $claim->update([
                'link_status' => $disetujui ? 'terverifikasi' : 'ditolak',
                'review_note' => $request->review_note,
                'verified_by' => $request->user()->id,
                'verified_at' => now(),
                'decided_at' => now(),
            ]);

            $claim->history()->create([
                'actor_user_id' => $request->user()->id,
                'action_name' => $disetujui ? 'verifikasi_disetujui' : 'verifikasi_ditolak',
                'from_status' => 'menunggu_verifikasi',
                'to_status' => $claim->link_status,
                'note' => $request->review_note,
            ]);

            if ($disetujui) {
                // Aktifkan akun user & tandai master pelaku sudah terhubung ke profil.
                $claim->user->update(['account_status' => 'aktif']);
                $claim->masterPelaku->update(['linked_profile_id' => $claim->profile_id]);
            }
        });

        return back()->with('success', 'Klaim KUSUKA berhasil diproses.');
    }
}