<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_master_pelaku_usaha', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('master_pelaku_id');
            $table->unsignedBigInteger('profile_id')->nullable();
            $table->string('relationship_type', 40)->default('pengelola');
            $table->string('link_status', 30)->default('menunggu_verifikasi');
            $table->unsignedBigInteger('claim_lock_key')->nullable();
            $table->unsignedInteger('user_claim_lock_key')->nullable();
            $table->unsignedInteger('handling_wilayah_id')->nullable();
            $table->unsignedTinyInteger('current_step')->default(3);
            $table->text('applicant_note')->nullable();
            $table->text('review_note')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->unsignedInteger('verified_by')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->dateTime('decided_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('master_pelaku_id')->references('id')->on('master_pelaku_usaha')->cascadeOnDelete();
            $table->foreign('profile_id')->references('id')->on('pelaku_usaha_profiles')->nullOnDelete();
            $table->foreign('handling_wilayah_id')->references('id')->on('wilayah')->nullOnDelete();
            $table->foreign('verified_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('user_master_pelaku_usaha_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('claim_id');
            $table->unsignedInteger('actor_user_id')->nullable();
            $table->string('action_name', 50);
            $table->string('from_status', 30)->nullable();
            $table->string('to_status', 30);
            $table->text('note')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('claim_id')->references('id')->on('user_master_pelaku_usaha')->cascadeOnDelete();
            $table->foreign('actor_user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_master_pelaku_usaha_history');
        Schema::dropIfExists('user_master_pelaku_usaha');
    }
};