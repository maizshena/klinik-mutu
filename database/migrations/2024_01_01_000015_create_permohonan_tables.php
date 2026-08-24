<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permohonan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nomor_tiket', 30)->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('wilayah_id')->nullable();
            $table->string('nama_pemohon', 255);
            $table->string('kontak_pemohon', 255);
            $table->string('jenis_layanan', 150)->default('Belum ditentukan');
            $table->text('kebutuhan');
            $table->enum('status', ['pending', 'diproses', 'selesai'])->default('pending');
            $table->unsignedInteger('current_step')->default(1);
            $table->string('approval_status', 50)->default('submitted');
            $table->text('admin_note')->nullable();
            $table->string('pendamping_mutu', 150)->nullable();
            $table->unsignedInteger('assigned_pembina_id')->nullable();
            $table->unsignedInteger('assigned_by')->nullable();
            $table->dateTime('assigned_at')->nullable();
            $table->string('conversation_state', 30)->default('menunggu_pembina');
            $table->string('closed_by', 255)->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->unsignedInteger('handling_wilayah_id')->nullable();
            $table->string('routing_reason', 500)->nullable();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('wilayah_id')->references('id')->on('wilayah')->nullOnDelete();
            $table->foreign('assigned_pembina_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('assigned_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('handling_wilayah_id')->references('id')->on('wilayah')->nullOnDelete();
        });

        Schema::create('permohonan_assignment_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('permohonan_id');
            $table->unsignedInteger('from_wilayah_id')->nullable();
            $table->unsignedInteger('to_wilayah_id')->nullable();
            $table->unsignedInteger('from_pembina_id')->nullable();
            $table->unsignedInteger('to_pembina_id')->nullable();
            $table->enum('action_type', ['routing_awal', 'penugasan', 'pengalihan', 'eskalasi', 'pengembalian']);
            $table->string('reason', 500)->nullable();
            $table->unsignedInteger('changed_by')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('permohonan_id')->references('id')->on('permohonan')->cascadeOnDelete();
            $table->foreign('from_wilayah_id')->references('id')->on('wilayah')->nullOnDelete();
            $table->foreign('to_wilayah_id')->references('id')->on('wilayah')->nullOnDelete();
            $table->foreign('from_pembina_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('to_pembina_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('changed_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('permohonan_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('permohonan_id');
            $table->unsignedInteger('uploaded_by')->nullable();
            $table->string('uploader_name', 255);
            $table->string('attachment_context', 40)->default('pengajuan_awal');
            $table->string('original_name', 255);
            $table->string('stored_name', 100);
            $table->string('mime_type', 150);
            $table->string('file_extension', 10);
            $table->unsignedBigInteger('file_size');
            $table->char('sha256', 64);
            $table->dateTime('verified_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('permohonan_id')->references('id')->on('permohonan')->cascadeOnDelete();
            $table->foreign('uploaded_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('permohonan_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('permohonan_id');
            $table->enum('sender_role', ['pemohon', 'pembina']);
            $table->string('sender_name', 255);
            $table->longText('message');
            $table->enum('message_format', ['text', 'html'])->default('text');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('permohonan_id')->references('id')->on('permohonan')->cascadeOnDelete();
        });

        Schema::create('permohonan_status_history', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('permohonan_id');
            $table->enum('status', ['pending', 'diproses', 'selesai'])->default('pending');
            $table->text('catatan')->nullable();
            $table->string('changed_by', 255)->default('Sistem');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('permohonan_id')->references('id')->on('permohonan')->cascadeOnDelete();
        });

        Schema::create('approval_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('permohonan_id');
            $table->unsignedInteger('step');
            $table->unsignedInteger('approver_id');
            $table->string('status', 50);
            $table->text('catatan')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('permohonan_id')->references('id')->on('permohonan')->cascadeOnDelete();
            $table->foreign('approver_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::create('survei_kepuasan', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('permohonan_id');
            $table->unsignedInteger('user_id');
            $table->unsignedTinyInteger('kecepatan');
            $table->unsignedTinyInteger('kejelasan');
            $table->unsignedTinyInteger('keramahan');
            $table->unsignedTinyInteger('manfaat');
            $table->unsignedTinyInteger('keseluruhan');
            $table->text('saran')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('permohonan_id')->references('id')->on('permohonan')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survei_kepuasan');
        Schema::dropIfExists('approval_history');
        Schema::dropIfExists('permohonan_status_history');
        Schema::dropIfExists('permohonan_messages');
        Schema::dropIfExists('permohonan_attachments');
        Schema::dropIfExists('permohonan_assignment_history');
        Schema::dropIfExists('permohonan');
    }
};