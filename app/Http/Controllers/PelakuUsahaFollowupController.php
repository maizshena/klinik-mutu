<?php

namespace App\Http\Controllers;

use App\Http\Requests\Followup\StoreFollowupRequest;
use App\Http\Requests\Followup\UpdateFollowupProgressRequest;
use App\Http\Requests\Followup\UploadFollowupEvidenceRequest;
use App\Http\Requests\Followup\VerifyFollowupRequest;
use App\Models\PelakuUsahaFollowup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class PelakuUsahaFollowupController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', PelakuUsahaFollowup::class);

        $user = $request->user();

        $followups = PelakuUsahaFollowup::query()
            ->with(['profile:id,nama_usaha', 'assignedPembina:id,nama_lengkap'])
            ->when(
                $user->role === 'pelaku_usaha',
                fn ($q) => $q->whereHas('profile', fn ($p) => $p->where('user_id', $user->id))
            )
            ->when(
                $user->role !== 'pelaku_usaha',
                fn ($q) => $q->where('assigned_pembina_id', $user->id)
            )
            ->when($request->filled('status'), fn ($q) => $q->where('workflow_status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Followup/Index', [
            'followups' => $followups,
            'filters' => $request->only('status'),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', PelakuUsahaFollowup::class);

        return Inertia::render('Followup/Create');
    }

    public function store(StoreFollowupRequest $request): RedirectResponse
    {
        $followup = PelakuUsahaFollowup::create([
            ...$request->validated(),
            'created_by' => $request->user()->id,
            'source_type' => $request->pembinaan_id ? 'pembinaan_proaktif' : ($request->konsultasi_teknis_id ? 'konsultasi_teknis' : 'manual'),
            'workflow_status' => 'belum_dimulai',
        ]);

        $followup->history()->create([
            'actor_user_id' => $request->user()->id,
            'actor_name' => $request->user()->nama_lengkap,
            'action_name' => 'pembuatan_rencana',
            'to_status' => 'belum_dimulai',
        ]);

        return redirect()
            ->route('followup.show', $followup)
            ->with('success', 'Rencana tindak lanjut berhasil dibuat.');
    }

    public function show(PelakuUsahaFollowup $followup): Response
    {
        $this->authorize('view', $followup);

        $followup->load(['profile', 'assignedPembina', 'evidence', 'history']);

        return Inertia::render('Followup/Show', [
            'followup' => $followup,
        ]);
    }

    /**
     * Pelaku usaha melaporkan progres perbaikan.
     */
    public function updateProgress(UpdateFollowupProgressRequest $request, PelakuUsahaFollowup $followup): RedirectResponse
    {
        DB::transaction(function () use ($request, $followup) {
            $fromStatus = $followup->workflow_status;

            $followup->update([
                'latest_progress' => $request->latest_progress,
                'workflow_status' => $request->workflow_status,
            ]);

            $followup->history()->create([
                'actor_user_id' => $request->user()->id,
                'actor_name' => $request->user()->nama_lengkap,
                'action_name' => 'update_progres',
                'from_status' => $fromStatus,
                'to_status' => $request->workflow_status,
                'note' => $request->latest_progress,
            ]);
        });

        return back()->with('success', 'Progres berhasil dilaporkan.');
    }

    /**
     * Upload bukti perbaikan (foto/dokumen) — disimpan privat dengan checksum SHA256.
     */
    public function uploadEvidence(UploadFollowupEvidenceRequest $request, PelakuUsahaFollowup $followup): RedirectResponse
    {
        $file = $request->file('file');
        $path = $file->store('followup-evidence', 'private');

        $followup->evidence()->create([
            'uploaded_by' => $request->user()->id,
            'note' => $request->note,
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => basename($path),
            'mime_type' => $file->getClientMimeType(),
            'file_extension' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'sha256' => hash_file('sha256', $file->getRealPath()),
            'review_status' => 'menunggu',
        ]);

        return back()->with('success', 'Bukti perbaikan berhasil diunggah.');
    }

    /**
     * Pembina memverifikasi bukti perbaikan → menandai tindak lanjut selesai atau minta perbaikan ulang.
     */
    public function verify(VerifyFollowupRequest $request, PelakuUsahaFollowup $followup): RedirectResponse
    {
        DB::transaction(function () use ($request, $followup) {
            $selesai = $request->decision === 'selesai';

            $followup->update([
                'workflow_status' => $selesai ? 'selesai' : 'sedang_dikerjakan',
                'verified_by' => $request->user()->id,
                'verified_at' => now(),
                'completed_at' => $selesai ? now() : null,
            ]);

            $followup->history()->create([
                'actor_user_id' => $request->user()->id,
                'actor_name' => $request->user()->nama_lengkap,
                'action_name' => $selesai ? 'verifikasi_selesai' : 'verifikasi_perlu_perbaikan',
                'to_status' => $followup->workflow_status,
                'note' => $request->review_note,
            ]);
        });

        return back()->with('success', 'Verifikasi tindak lanjut berhasil disimpan.');
    }
}