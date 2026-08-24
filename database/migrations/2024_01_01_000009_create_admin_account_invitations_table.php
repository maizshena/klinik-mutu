<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_account_invitations', function (Blueprint $table) {
            $table->id();
            $table->char('code_hash', 64);
            $table->char('code_suffix', 4);
            $table->unsignedInteger('requested_role_id');
            $table->unsignedInteger('wilayah_id');
            $table->string('candidate_name', 255);
            $table->string('email', 255);
            $table->string('whatsapp', 20);
            $table->unsignedBigInteger('pembina_master_id')->nullable();
            $table->enum('status', ['aktif', 'digunakan', 'dibatalkan', 'kedaluwarsa'])->default('aktif');
            $table->dateTime('expires_at');
            $table->unsignedInteger('created_by');
            $table->timestamp('created_at')->useCurrent();
            $table->unsignedInteger('used_by_user_id')->nullable();
            $table->dateTime('used_at')->nullable();
            $table->unsignedInteger('cancelled_by')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->unsignedInteger('revision_count')->default(0);
            $table->text('catatan')->nullable();

            $table->foreign('requested_role_id')->references('id')->on('roles')->cascadeOnDelete();
            $table->foreign('wilayah_id')->references('id')->on('wilayah')->cascadeOnDelete();
            $table->foreign('pembina_master_id')->references('id')->on('pembina_mutu_master')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('used_by_user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('cancelled_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_account_invitations');
    }
};