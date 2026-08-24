<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelaku_usaha_followups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profile_id');
            $table->unsignedBigInteger('master_pelaku_id')->nullable();
            $table->string('source_type', 30)->default('manual');
            $table->unsignedBigInteger('pembinaan_id')->nullable();
            $table->unsignedInteger('permohonan_id')->nullable();
            $table->unsignedBigInteger('konsultasi_teknis_id')->nullable();
            $table->string('title', 255);
            $table->text('finding');
            $table->text('action_plan');
            $table->string('responsible_party', 30)->default('pelaku_usaha');
            $table->unsignedInteger('assigned_pembina_id');
            $table->unsignedInteger('created_by');
            $table->string('priority', 20)->default('normal');
            $table->date('start_date');
            $table->date('due_date');
            $table->string('workflow_status', 40)->default('belum_dimulai');
            $table->text('latest_progress')->nullable();
            $table->unsignedInteger('verified_by')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('profile_id')->references('id')->on('pelaku_usaha_profiles')->cascadeOnDelete();
            $table->foreign('master_pelaku_id')->references('id')->on('master_pelaku_usaha')->nullOnDelete();
            $table->foreign('pembinaan_id')->references('id')->on('pembinaan_proaktif')->nullOnDelete();
            $table->foreign('permohonan_id')->references('id')->on('permohonan')->nullOnDelete();
            $table->foreign('konsultasi_teknis_id')->references('id')->on('konsultasi_teknis')->nullOnDelete();
            $table->foreign('assigned_pembina_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('verified_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('pelaku_usaha_followup_evidence', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('followup_id');
            $table->unsignedInteger('uploaded_by');
            $table->text('note')->nullable();
            $table->string('original_name', 255)->nullable();
            $table->string('stored_name', 255)->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->string('file_extension', 20)->nullable();
            $table->unsignedInteger('file_size')->default(0);
            $table->char('sha256', 64)->nullable();
            $table->string('review_status', 30)->default('menunggu');
            $table->unsignedInteger('reviewed_by')->nullable();
            $table->text('review_note')->nullable();
            $table->dateTime('reviewed_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('followup_id')->references('id')->on('pelaku_usaha_followups')->cascadeOnDelete();
            $table->foreign('uploaded_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('pelaku_usaha_followup_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('followup_id');
            $table->unsignedInteger('actor_user_id')->nullable();
            $table->string('actor_name', 255);
            $table->string('action_name', 60);
            $table->string('from_status', 40)->nullable();
            $table->string('to_status', 40);
            $table->text('note')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('followup_id')->references('id')->on('pelaku_usaha_followups')->cascadeOnDelete();
            $table->foreign('actor_user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelaku_usaha_followup_history');
        Schema::dropIfExists('pelaku_usaha_followup_evidence');
        Schema::dropIfExists('pelaku_usaha_followups');
    }
};