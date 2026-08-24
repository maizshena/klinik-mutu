<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembinaan_proaktif', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pembinaan', 40)->nullable();
            $table->unsignedBigInteger('profile_id')->nullable();
            $table->unsignedInteger('target_province_id')->nullable();
            $table->unsignedInteger('target_district_id')->nullable();
            $table->string('directive_type', 60);
            $table->date('directive_date');
            $table->string('directive_number', 150)->nullable();
            $table->text('directive_note')->nullable();
            $table->text('tujuan');
            $table->text('ruang_lingkup');
            $table->string('prioritas', 20)->default('normal');
            $table->date('target_date')->nullable();
            $table->string('status', 50)->default('menunggu_penugasan');
            $table->unsignedInteger('assigned_pembina_id')->nullable();
            $table->unsignedInteger('assigned_by')->nullable();
            $table->dateTime('assigned_at')->nullable();
            $table->string('contact_person', 255)->nullable();
            $table->string('coordination_channel', 60)->nullable();
            $table->text('coordination_result')->nullable();
            $table->dateTime('scheduled_at')->nullable();
            $table->string('method', 60)->nullable();
            $table->longText('findings')->nullable();
            $table->longText('recommendations')->nullable();
            $table->longText('followup_summary')->nullable();
            $table->unsignedInteger('completion_requested_by')->nullable();
            $table->dateTime('completion_requested_at')->nullable();
            $table->unsignedInteger('completed_by')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->text('cancelled_reason')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('profile_id')->references('id')->on('pelaku_usaha_profiles')->nullOnDelete();
            $table->foreign('target_province_id')->references('id')->on('wilayah')->nullOnDelete();
            $table->foreign('target_district_id')->references('id')->on('wilayah')->nullOnDelete();
            $table->foreign('assigned_pembina_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('assigned_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('completion_requested_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('completed_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('pembinaan_proaktif_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pembinaan_id');
            $table->unsignedInteger('uploaded_by')->nullable();
            $table->string('category', 60)->default('dokumentasi');
            $table->string('original_name', 255);
            $table->string('stored_name', 255);
            $table->string('mime_type', 100);
            $table->unsignedInteger('file_size');
            $table->char('sha256', 64)->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('pembinaan_id')->references('id')->on('pembinaan_proaktif')->cascadeOnDelete();
            $table->foreign('uploaded_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('pembinaan_proaktif_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pembinaan_id');
            $table->string('from_status', 50)->nullable();
            $table->string('to_status', 50);
            $table->string('action_type', 80);
            $table->unsignedInteger('actor_id')->nullable();
            $table->string('actor_name', 255);
            $table->text('note')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('pembinaan_id')->references('id')->on('pembinaan_proaktif')->cascadeOnDelete();
            $table->foreign('actor_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('pembinaan_proaktif_team_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pembinaan_id');
            $table->unsignedInteger('pembina_id');
            $table->string('member_role', 20)->default('anggota');
            $table->unsignedInteger('assigned_by')->nullable();
            $table->text('assignment_note')->nullable();
            $table->boolean('is_active')->default(true);
            $table->dateTime('assigned_at')->useCurrent();
            $table->dateTime('ended_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('pembinaan_id')->references('id')->on('pembinaan_proaktif')->cascadeOnDelete();
            $table->foreign('pembina_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('assigned_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembinaan_proaktif_team_members');
        Schema::dropIfExists('pembinaan_proaktif_history');
        Schema::dropIfExists('pembinaan_proaktif_attachments');
        Schema::dropIfExists('pembinaan_proaktif');
    }
};