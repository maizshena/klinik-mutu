<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('petugas_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('requested_role_id');
            $table->unsignedInteger('wilayah_id')->nullable();
            $table->unsignedBigInteger('admin_invitation_id')->nullable();
            $table->unsignedBigInteger('pembina_master_id')->nullable();
            $table->string('nip_identitas', 80);
            $table->string('unit_kerja', 255);
            $table->string('jabatan', 150);
            $table->text('bidang_keahlian')->nullable();
            $table->string('nomor_surat_tugas', 150)->nullable();
            $table->date('tanggal_surat_tugas')->nullable();
            $table->string('pejabat_penandatangan', 255)->nullable();
            $table->date('masa_tugas_mulai')->nullable();
            $table->date('masa_tugas_akhir')->nullable();
            $table->text('catatan_pemohon')->nullable();
            $table->enum('status', [
                'menunggu_verifikasi', 'perlu_perbaikan', 'disetujui', 'ditolak', 'dibatalkan',
            ])->default('menunggu_verifikasi');
            $table->text('catatan_verifikator')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->unsignedInteger('reviewed_by')->nullable();
            $table->dateTime('reviewed_at')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('requested_role_id')->references('id')->on('roles')->cascadeOnDelete();
            $table->foreign('wilayah_id')->references('id')->on('wilayah')->nullOnDelete();
            $table->foreign('admin_invitation_id')->references('id')->on('admin_account_invitations')->nullOnDelete();
            $table->foreign('pembina_master_id')->references('id')->on('pembina_mutu_master')->nullOnDelete();
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('petugas_registration_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registration_id');
            $table->string('document_type', 40)->default('surat_tugas');
            $table->string('original_name', 255);
            $table->string('stored_name', 120);
            $table->string('mime_type', 100);
            $table->unsignedInteger('file_size');
            $table->char('sha256', 64);
            $table->unsignedInteger('verified_by')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->timestamp('uploaded_at')->useCurrent();

            $table->foreign('registration_id')->references('id')->on('petugas_registrations')->cascadeOnDelete();
            $table->foreign('verified_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('petugas_registration_kompetensi', function (Blueprint $table) {
            $table->unsignedBigInteger('registration_id');
            $table->unsignedInteger('kompetensi_id');
            $table->string('kompetensi_lainnya', 500)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->primary(['registration_id', 'kompetensi_id']);

            $table->foreign('registration_id')->references('id')->on('petugas_registrations')->cascadeOnDelete();
            $table->foreign('kompetensi_id')->references('id')->on('kompetensi_master')->cascadeOnDelete();
        });

        Schema::create('petugas_verification_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registration_id');
            $table->unsignedInteger('action_by')->nullable();
            $table->string('action_type', 40);
            $table->text('catatan')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('registration_id')->references('id')->on('petugas_registrations')->cascadeOnDelete();
            $table->foreign('action_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petugas_verification_history');
        Schema::dropIfExists('petugas_registration_kompetensi');
        Schema::dropIfExists('petugas_registration_documents');
        Schema::dropIfExists('petugas_registrations');
    }
};