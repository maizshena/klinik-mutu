<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\MasterPelakuUsaha;
use App\Models\PelakuUsahaProfile;
use App\Models\Role;
use App\Models\User;
use App\Models\UserMasterPelakuUsaha;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = DB::transaction(function () use ($request) {
            $jalur = $request->validated('jalur_pendaftaran');
            $roleModel = Role::where('nama_role', 'pelaku_usaha')->first();

            $user = User::create([
                'nama_lengkap' => $request->nama_lengkap,
                'profesi' => 'Pelaku Usaha',
                'nama_usaha' => $request->nama_usaha ?? '-',
                'email' => $request->email,
                'role_id' => $roleModel?->id,
                'whatsapp' => $request->whatsapp,
                'password_hash' => Hash::make($request->password),
                'role' => 'pelaku_usaha',
                // Jalur KUSUKA wajib menunggu verifikasi admin (guardrail anti-duplikasi identitas).
                'account_status' => $jalur === 'kusuka' ? 'menunggu_verifikasi' : 'aktif',
            ]);

            if ($jalur === 'manual') {
                $this->buatProfilManual($request, $user);
            } else {
                $this->ajukanKlaimKusuka($request, $user);
            }

            return $user;
        });

        Auth::login($user);

        return redirect()->route('dashboard')->with(
            'success',
            $user->account_status === 'menunggu_verifikasi'
                ? 'Pendaftaran berhasil. Klaim KUSUKA kamu sedang menunggu verifikasi admin.'
                : 'Pendaftaran berhasil. Selamat datang di Klinik Mutu!'
        );
    }

    private function buatProfilManual(RegisterRequest $request, User $user): void
    {
        PelakuUsahaProfile::create([
            'user_id' => $user->id,
            'nama_usaha' => $request->nama_usaha,
            'nama_penanggung_jawab' => $request->nama_lengkap,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'alamat' => $request->alamat,
            'provinsi_id' => $request->provinsi_id,
            'kabupaten_id' => $request->kabupaten_id,
            'komoditas' => $request->komoditas,
            'source_type' => 'pendaftaran_mandiri',
            'account_state' => 'terhubung',
            'created_by' => $user->id,
        ]);
    }

    private function ajukanKlaimKusuka(RegisterRequest $request, User $user): void
    {
        $master = MasterPelakuUsaha::where('no_kusuka', $request->no_kusuka)->firstOrFail();

        $claim = UserMasterPelakuUsaha::create([
            'user_id' => $user->id,
            'master_pelaku_id' => $master->id,
            'relationship_type' => 'pengelola',
            'link_status' => 'menunggu_verifikasi',
            'handling_wilayah_id' => $master->kabupaten_wilayah_id,
            'current_step' => 3,
            'applicant_note' => $request->catatan_klaim,
            'is_primary' => true,
        ]);

        $claim->history()->create([
            'actor_user_id' => $user->id,
            'action_name' => 'pengajuan_klaim',
            'to_status' => 'menunggu_verifikasi',
            'note' => 'Klaim diajukan otomatis saat registrasi.',
        ]);
    }
}