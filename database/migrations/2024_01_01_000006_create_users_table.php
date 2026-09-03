<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_lengkap', 255);
            $table->string('profesi', 100);
            $table->string('nama_usaha', 255);
            $table->string('provinsi', 100)->default('');
            $table->string('email', 255)->unique();
            $table->unsignedInteger('role_id')->nullable();
            $table->string('whatsapp', 50);
            $table->string('password_hash', 255);
            $table->string('role', 50)->default('pelaku_usaha');
            $table->enum('account_status', [
                'aktif', 'menunggu_verifikasi', 'perlu_perbaikan', 'ditolak', 'nonaktif',
            ])->default('aktif');
            $table->unsignedInteger('verified_by')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->dateTime('last_login_at')->nullable();
            $table->unsignedInteger('auth_version')->default(1);
            $table->dateTime('password_changed_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->string('kabupaten', 255)->default('');

            $table->foreign('role_id')->references('id')->on('roles')->nullOnDelete();
            $table->foreign('verified_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['verified_by']);
        });
        Schema::dropIfExists('users');
    }
};