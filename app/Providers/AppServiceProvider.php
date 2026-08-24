<?php

namespace App\Providers;

use App\Models\KonsultasiTeknis;
use App\Models\Permohonan;
use App\Policies\KonsultasiTeknisPolicy;
use App\Policies\PermohonanPolicy;
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
    }
}