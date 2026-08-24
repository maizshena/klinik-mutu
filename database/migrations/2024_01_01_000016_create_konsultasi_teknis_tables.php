<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konsultasi_teknis', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_konsultasi', 40)->nullable();
            $table->unsignedInteger('origin_wilayah_id');
            $table->string('origin_wilayah_label', 255);
            $table->unsignedInteger('created_by')->nullable();
            $table->string('creator_name', 255);
            $table->string('kategori', 100);
            $table->string('judul', 255);
            $table->string('komoditas', 150)->nullable();
            $table->string('jenis_usaha', 150)->nullable();
            $table->longText('uraian_masalah');
            $table->text('hasil_diharapkan')->nullable();
            $table->string('status', 40)->default('draft');
            $table->unsignedInteger('assigned_pembina_id')->nullable();
            $table->unsignedInteger('assigned_by')->nullable();
            $table->dateTime('assigned_at')->nullable();
            $table->text('catatan_pusat')->nullable();
            $table->longText('jawaban_teknis')->nullable();
            $table->unsignedInteger('answered_by')->nullable();
            $table->dateTime('answered_at')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('last_resubmitted_at')->nullable();
            $table->text('sender_followup_note')->nullable();
            $table->dateTime('followup_requested_at')->nullable();
            $table->unsignedInteger('completed_by')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->unsignedInteger('closed_by')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('origin_wilayah_id')->references('id')->on('wilayah')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('assigned_pembina_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('assigned_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('answered_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('completed_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('closed_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('konsultasi_teknis_interactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('konsultasi_id');
            $table->string('interaction_type', 40);
            $table->unsignedInteger('actor_id')->nullable();
            $table->string('actor_name', 255);
            $table->longText('content');
            $table->dateTime('created_at')->useCurrent();

            $table->foreign('konsultasi_id')->references('id')->on('konsultasi_teknis')->cascadeOnDelete();
            $table->foreign('actor_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('konsultasi_teknis_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('konsultasi_id');
            $table->unsignedInteger('uploaded_by')->nullable();
            $table->string('uploader_name', 255);
            $table->string('attachment_context', 40)->default('pengajuan_awal');
            $table->unsignedBigInteger('interaction_id')->nullable();
            $table->string('original_name', 255);
            $table->string('stored_name', 100);
            $table->string('mime_type', 150);
            $table->string('file_extension', 10);
            $table->unsignedBigInteger('file_size');
            $table->char('sha256', 64);
            $table->dateTime('verified_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('konsultasi_id')->references('id')->on('konsultasi_teknis')->cascadeOnDelete();
            $table->foreign('uploaded_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('interaction_id')->references('id')->on('konsultasi_teknis_interactions')->nullOnDelete();
        });

        Schema::create('konsultasi_teknis_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('konsultasi_id');
            $table->string('from_status', 40)->nullable();
            $table->string('to_status', 40);
            $table->string('action_type', 60);
            $table->unsignedInteger('actor_id')->nullable();
            $table->string('actor_name', 255);
            $table->text('note')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('konsultasi_id')->references('id')->on('konsultasi_teknis')->cascadeOnDelete();
            $table->foreign('actor_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konsultasi_teknis_history');
        Schema::dropIfExists('konsultasi_teknis_attachments');
        Schema::dropIfExists('konsultasi_teknis_interactions');
        Schema::dropIfExists('konsultasi_teknis');
    }
};