<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('konsultasi_teknis', function (Blueprint $table) {
            // Guardrail sistem: "Semua modul transaksi harus terhubung ke profil pelaku."
            $table->unsignedBigInteger('profile_id')->nullable()->after('created_by');
            $table->unsignedBigInteger('master_pelaku_id')->nullable()->after('profile_id');

            $table->foreign('profile_id')->references('id')->on('pelaku_usaha_profiles')->nullOnDelete();
            $table->foreign('master_pelaku_id')->references('id')->on('master_pelaku_usaha')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('konsultasi_teknis', function (Blueprint $table) {
            $table->dropForeign(['profile_id']);
            $table->dropForeign(['master_pelaku_id']);
            $table->dropColumn(['profile_id', 'master_pelaku_id']);
        });
    }
};