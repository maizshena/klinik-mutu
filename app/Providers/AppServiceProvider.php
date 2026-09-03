<?php

namespace App\Providers;

use App\Models\KonsultasiTeknis;
use App\Models\PelakuUsahaFollowup;
use App\Models\PembinaanProaktif;
use App\Models\Permohonan;
use App\Models\UserMasterPelakuUsaha;
use App\Policies\KonsultasiTeknisPolicy;
use App\Policies\PelakuUsahaFollowupPolicy;
use App\Policies\PembinaanProaktifPolicy;
use App\Policies\PermohonanPolicy;
use App\Policies\UserMasterPelakuUsahaPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Permohonan::class, PermohonanPolicy::class);
        Gate::policy(KonsultasiTeknis::class, KonsultasiTeknisPolicy::class);
        Gate::policy(PembinaanProaktif::class, PembinaanProaktifPolicy::class);
        Gate::policy(PelakuUsahaFollowup::class, PelakuUsahaFollowupPolicy::class);
        Gate::policy(UserMasterPelakuUsaha::class, UserMasterPelakuUsahaPolicy::class);
    }
}