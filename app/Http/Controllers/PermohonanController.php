<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleType;
use App\Http\Controllers\Concerns\ScopesByWilayah;
use App\Http\Requests\Permohonan\AssignPermohonanRequest;
use App\Http\Requests\Permohonan\StorePermohonanRequest;
use App\Http\Requests\Permohonan\UpdatePermohonanRequest;
use App\Models\Permohonan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class PermohonanController extends Controller
{
    use ScopesByWilayah;

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Permohonan::class);

        $user = $request->user();
        $role = UserRoleType::tryFrom($user->role);

        $query = Permohonan::query()
            ->with(['wilayah:id,nama_wilayah', 'assignedPembina:id,nama_lengkap']);

        if ($role === UserRoleType::PELAKU_USAHA) {
            $query->where('user_id', $user->id);
        } else {
            $query = $this->applyWilayahScope($query, $user, 'handling_wilayah_id');
        }

        $permohonan = $query
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Permohonan/Index', [
            'permohonan' => $permohonan,
            'filters' => $request->only('status'),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Permohonan::class);

        return Inertia::render('Permohonan/Create');
    }

    public function store(StorePermohonanRequest $request): RedirectResponse
    {
        $permohonan = DB::transaction(function () use ($request) {
            $permohonan = Permohonan::create([
                ...$request->validated(),
                'user_id' => $request->user()->id,
                'nomor_tiket' => $this->generateNomorTiket(),
                'nama_pemohon' => $request->user()->nama_lengkap,
            ]);

            $permohonan->statusHistory()->create([
                'status' => 'pending',
                'changed_by' => $request->user()->nama_lengkap,
            ]);

            return $permohonan;
        });

        return redirect()
            ->route('permohonan.show', $permohonan)
            ->with('success', 'Permohonan berhasil diajukan.');
    }

    public function show(Permohonan $permohonan): Response
    {
        $this->authorize('view', $permohonan);

        $permohonan->load([
            'wilayah', 'assignedPembina', 'attachments', 'messages', 'statusHistory',
        ]);

        return Inertia::render('Permohonan/Show', [
            'permohonan' => $permohonan,
        ]);
    }

    public function update(UpdatePermohonanRequest $request, Permohonan $permohonan): RedirectResponse
    {
        $permohonan->update($request->validated());

        return back()->with('success', 'Permohonan berhasil diperbarui.');
    }

    public function destroy(Permohonan $permohonan): RedirectResponse
    {
        $this->authorize('delete', $permohonan);

        $permohonan->delete();

        return redirect()->route('permohonan.index')
            ->with('success', 'Permohonan berhasil dibatalkan.');
    }

    public function assign(AssignPermohonanRequest $request, Permohonan $permohonan): RedirectResponse
    {
        DB::transaction(function () use ($request, $permohonan) {
            $permohonan->assignmentHistory()->create([
                'from_pembina_id' => $permohonan->assigned_pembina_id,
                'to_pembina_id' => $request->assigned_pembina_id,
                'to_wilayah_id' => $permohonan->handling_wilayah_id,
                'action_type' => 'penugasan',
                'reason' => $request->reason,
                'changed_by' => $request->user()->id,
            ]);

            $permohonan->update([
                'assigned_pembina_id' => $request->assigned_pembina_id,
                'assigned_by' => $request->user()->id,
                'assigned_at' => now(),
                'status' => 'diproses',
                'conversation_state' => 'menunggu_pembina',
            ]);
        });

        return back()->with('success', 'Permohonan berhasil ditugaskan.');
    }

    private function generateNomorTiket(): string
    {
        return 'KM-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
    }
}