<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleType;
use App\Http\Controllers\Concerns\ScopesByWilayah;
use App\Http\Requests\KonsultasiTeknis\JawabKonsultasiTeknisRequest;
use App\Http\Requests\KonsultasiTeknis\StoreKonsultasiTeknisRequest;
use App\Http\Requests\KonsultasiTeknis\UpdateKonsultasiTeknisRequest;
use App\Models\KonsultasiTeknis;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class KonsultasiTeknisController extends Controller
{
    use ScopesByWilayah;

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', KonsultasiTeknis::class);

        $user = $request->user();
        $role = UserRoleType::tryFrom($user->role);

        $query = KonsultasiTeknis::query()
            ->with(['originWilayah:id,nama_wilayah', 'assignedPembina:id,nama_lengkap']);

        if ($role === UserRoleType::PELAKU_USAHA) {
            $query->where('created_by', $user->id);
        } else {
            $query = $this->applyWilayahScope($query, $user, 'origin_wilayah_id');
        }

        $konsultasi = $query
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('KonsultasiTeknis/Index', [
            'konsultasi' => $konsultasi,
            'filters' => $request->only('status'),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', KonsultasiTeknis::class);

        return Inertia::render('KonsultasiTeknis/Create');
    }

    public function store(StoreKonsultasiTeknisRequest $request): RedirectResponse
    {
        $konsultasi = DB::transaction(function () use ($request) {
            $konsultasi = KonsultasiTeknis::create([
                ...$request->safe()->except('lampiran'),
                'created_by' => $request->user()->id,
                'creator_name' => $request->user()->nama_lengkap,
                'origin_wilayah_label' => $request->user()->kabupaten,
                'nomor_konsultasi' => $this->generateNomorKonsultasi(),
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);

            foreach ($request->file('lampiran', []) as $file) {
                $path = $file->store('konsultasi-teknis', 'private');

                $konsultasi->attachments()->create([
                    'uploaded_by' => $request->user()->id,
                    'uploader_name' => $request->user()->nama_lengkap,
                    'original_name' => $file->getClientOriginalName(),
                    'stored_name' => basename($path),
                    'mime_type' => $file->getClientMimeType(),
                    'file_extension' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                    'sha256' => hash_file('sha256', $file->getRealPath()),
                ]);
            }

            $konsultasi->history()->create([
                'to_status' => 'submitted',
                'action_type' => 'pengajuan',
                'actor_id' => $request->user()->id,
                'actor_name' => $request->user()->nama_lengkap,
            ]);

            return $konsultasi;
        });

        return redirect()
            ->route('konsultasi-teknis.show', $konsultasi)
            ->with('success', 'Konsultasi teknis berhasil diajukan.');
    }

    public function show(KonsultasiTeknis $konsultasiTeknis): Response
    {
        $this->authorize('view', $konsultasiTeknis);

        $konsultasiTeknis->load([
            'originWilayah', 'assignedPembina', 'attachments', 'interactions', 'history',
            'profile', 'masterPelaku',
        ]);

        return Inertia::render('KonsultasiTeknis/Show', [
            'konsultasi' => $konsultasiTeknis,
        ]);
    }

    public function update(UpdateKonsultasiTeknisRequest $request, KonsultasiTeknis $konsultasiTeknis): RedirectResponse
    {
        $konsultasiTeknis->update($request->validated());

        return back()->with('success', 'Konsultasi berhasil diperbarui.');
    }

    public function destroy(KonsultasiTeknis $konsultasiTeknis): RedirectResponse
    {
        $this->authorize('delete', $konsultasiTeknis);

        $konsultasiTeknis->delete();

        return redirect()->route('konsultasi-teknis.index')
            ->with('success', 'Konsultasi berhasil dihapus.');
    }

    public function jawab(JawabKonsultasiTeknisRequest $request, KonsultasiTeknis $konsultasiTeknis): RedirectResponse
    {
        DB::transaction(function () use ($request, $konsultasiTeknis) {
            $konsultasiTeknis->update([
                'jawaban_teknis' => $request->jawaban_teknis,
                'catatan_pusat' => $request->catatan_pusat,
                'answered_by' => $request->user()->id,
                'answered_at' => now(),
                'status' => 'selesai',
            ]);

            $konsultasiTeknis->history()->create([
                'from_status' => 'diproses',
                'to_status' => 'selesai',
                'action_type' => 'jawaban_teknis',
                'actor_id' => $request->user()->id,
                'actor_name' => $request->user()->nama_lengkap,
            ]);

            $konsultasiTeknis->interactions()->create([
                'interaction_type' => 'jawaban',
                'actor_id' => $request->user()->id,
                'actor_name' => $request->user()->nama_lengkap,
                'content' => $request->jawaban_teknis,
            ]);
        });

        return back()->with('success', 'Jawaban teknis berhasil dikirim.');
    }

    private function generateNomorKonsultasi(): string
    {
        return 'KT-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
    }
}