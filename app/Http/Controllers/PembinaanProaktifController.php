<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleType;
use App\Http\Controllers\Concerns\ScopesByWilayah;
use App\Http\Requests\PembinaanProaktif\AssignPembinaanProaktifRequest;
use App\Http\Requests\PembinaanProaktif\CompletePembinaanProaktifRequest;
use App\Http\Requests\PembinaanProaktif\StorePembinaanProaktifRequest;
use App\Http\Requests\PembinaanProaktif\SubmitFindingsRequest;
use App\Models\PembinaanProaktif;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class PembinaanProaktifController extends Controller
{
    use ScopesByWilayah;

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', PembinaanProaktif::class);

        $user = $request->user();

        $query = PembinaanProaktif::query()
            ->with(['targetProvince:id,nama_wilayah', 'targetDistrict:id,nama_wilayah', 'assignedPembina:id,nama_lengkap']);

        if (UserRoleType::tryFrom($user->role) === UserRoleType::PEMBINA_DAERAH) {
            $query->where('assigned_pembina_id', $user->id);
        } else {
            $query = $this->applyWilayahScope($query, $user, 'target_district_id');
        }

        $pembinaan = $query
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('PembinaanProaktif/Index', [
            'pembinaan' => $pembinaan,
            'filters' => $request->only('status'),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', PembinaanProaktif::class);

        return Inertia::render('PembinaanProaktif/Create');
    }

    /**
     * Admin membuat penugasan pembinaan proaktif (masih tanpa pembina — tahap berikutnya `assign`).
     */
    public function store(StorePembinaanProaktifRequest $request): RedirectResponse
    {
        $pembinaan = PembinaanProaktif::create([
            ...$request->validated(),
            'nomor_pembinaan' => 'PB-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
            'status' => 'menunggu_penugasan',
            'created_by' => $request->user()->id,
        ]);

        $pembinaan->history()->create([
            'to_status' => 'menunggu_penugasan',
            'action_type' => 'pembuatan_penugasan',
            'actor_id' => $request->user()->id,
            'actor_name' => $request->user()->nama_lengkap,
        ]);

        return redirect()
            ->route('pembinaan-proaktif.show', $pembinaan)
            ->with('success', 'Penugasan pembinaan proaktif berhasil dibuat.');
    }

    public function show(PembinaanProaktif $pembinaanProaktif): Response
    {
        $this->authorize('view', $pembinaanProaktif);

        $pembinaanProaktif->load([
            'profile', 'targetProvince', 'targetDistrict', 'assignedPembina',
            'attachments', 'history', 'teamMembers.pembina:id,nama_lengkap',
        ]);

        return Inertia::render('PembinaanProaktif/Show', [
            'pembinaan' => $pembinaanProaktif,
        ]);
    }

    public function assign(AssignPembinaanProaktifRequest $request, PembinaanProaktif $pembinaanProaktif): RedirectResponse
    {
        DB::transaction(function () use ($request, $pembinaanProaktif) {
            $pembinaanProaktif->update([
                'assigned_pembina_id' => $request->assigned_pembina_id,
                'assigned_by' => $request->user()->id,
                'assigned_at' => now(),
                'contact_person' => $request->contact_person,
                'coordination_channel' => $request->coordination_channel,
                'status' => 'sedang_dilaksanakan',
            ]);

            $pembinaanProaktif->history()->create([
                'from_status' => 'menunggu_penugasan',
                'to_status' => 'sedang_dilaksanakan',
                'action_type' => 'penugasan_pembina',
                'actor_id' => $request->user()->id,
                'actor_name' => $request->user()->nama_lengkap,
            ]);
        });

        return back()->with('success', 'Pembina berhasil ditugaskan.');
    }

    /**
     * Pembina di lapangan mengunggah temuan, rekomendasi, dan dokumentasi.
     */
    public function submitFindings(SubmitFindingsRequest $request, PembinaanProaktif $pembinaanProaktif): RedirectResponse
    {
        DB::transaction(function () use ($request, $pembinaanProaktif) {
            $pembinaanProaktif->update([
                'findings' => $request->findings,
                'recommendations' => $request->recommendations,
                'followup_summary' => $request->followup_summary,
                'completion_requested_by' => $request->user()->id,
                'completion_requested_at' => now(),
                'status' => 'menunggu_persetujuan',
            ]);

            foreach ($request->file('lampiran', []) as $file) {
                $path = $file->store('pembinaan-proaktif', 'private');

                $pembinaanProaktif->attachments()->create([
                    'uploaded_by' => $request->user()->id,
                    'category' => 'dokumentasi',
                    'original_name' => $file->getClientOriginalName(),
                    'stored_name' => basename($path),
                    'mime_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                    'sha256' => hash_file('sha256', $file->getRealPath()),
                ]);
            }

            $pembinaanProaktif->history()->create([
                'from_status' => 'sedang_dilaksanakan',
                'to_status' => 'menunggu_persetujuan',
                'action_type' => 'pengisian_temuan',
                'actor_id' => $request->user()->id,
                'actor_name' => $request->user()->nama_lengkap,
            ]);
        });

        return back()->with('success', 'Temuan & rekomendasi berhasil dikirim untuk persetujuan.');
    }

    /**
     * Admin menyetujui penyelesaian — riwayat otomatis masuk ke profil pelaku (guardrail #3).
     */
    public function complete(CompletePembinaanProaktifRequest $request, PembinaanProaktif $pembinaanProaktif): RedirectResponse
    {
        DB::transaction(function () use ($request, $pembinaanProaktif) {
            $pembinaanProaktif->update([
                'completed_by' => $request->user()->id,
                'completed_at' => now(),
                'status' => 'selesai',
            ]);

            $pembinaanProaktif->history()->create([
                'from_status' => 'menunggu_persetujuan',
                'to_status' => 'selesai',
                'action_type' => 'persetujuan_penyelesaian',
                'actor_id' => $request->user()->id,
                'actor_name' => $request->user()->nama_lengkap,
                'note' => $request->catatan_penyelesaian,
            ]);
        });

        return back()->with('success', 'Pembinaan proaktif dinyatakan selesai.');
    }

    public function cancel(Request $request, PembinaanProaktif $pembinaanProaktif): RedirectResponse
    {
        $this->authorize('cancel', $pembinaanProaktif);

        $request->validate(['cancelled_reason' => ['required', 'string', 'max:500']]);

        $pembinaanProaktif->update([
            'status' => 'dibatalkan',
            'cancelled_reason' => $request->cancelled_reason,
        ]);

        $pembinaanProaktif->history()->create([
            'to_status' => 'dibatalkan',
            'action_type' => 'pembatalan',
            'actor_id' => $request->user()->id,
            'actor_name' => $request->user()->nama_lengkap,
            'note' => $request->cancelled_reason,
        ]);

        return back()->with('success', 'Pembinaan proaktif dibatalkan.');
    }
}