<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelaku_usaha_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('nama_usaha', 255);
            $table->string('nama_penanggung_jawab', 255);
            $table->string('nib', 50)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('whatsapp', 30)->nullable();
            $table->text('alamat')->nullable();
            $table->unsignedInteger('provinsi_id')->nullable();
            $table->unsignedInteger('kabupaten_id')->nullable();
            $table->string('provinsi_name', 150)->nullable();
            $table->string('kabupaten_name', 150)->nullable();
            $table->string('kecamatan', 150)->nullable();
            $table->string('komoditas', 255)->nullable();
            $table->text('produk')->nullable();
            $table->string('skala_usaha', 60)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('source_type', 60)->default('pendataan_petugas');
            $table->string('account_state', 40)->default('belum_memiliki_akun');
            $table->unsignedBigInteger('merged_into_profile_id')->nullable();
            $table->dateTime('merged_at')->nullable();
            $table->unsignedInteger('merged_by')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('provinsi_id')->references('id')->on('wilayah')->nullOnDelete();
            $table->foreign('kabupaten_id')->references('id')->on('wilayah')->nullOnDelete();
            $table->foreign('merged_into_profile_id')->references('id')->on('pelaku_usaha_profiles')->nullOnDelete();
            $table->foreign('merged_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('pelaku_usaha_account_invitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profile_id');
            $table->char('token_hash', 64);
            $table->unsignedInteger('requested_by')->nullable();
            $table->dateTime('expires_at');
            $table->dateTime('used_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('profile_id')->references('id')->on('pelaku_usaha_profiles')->cascadeOnDelete();
            $table->foreign('requested_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelaku_usaha_account_invitations');
        Schema::dropIfExists('pelaku_usaha_profiles');
    }
};