<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('scope', 30);
            $table->char('identifier_hash', 64);
            $table->char('ip_hash', 64);
            $table->boolean('success')->default(false);
            $table->timestamp('attempted_at')->useCurrent();

            $table->index(['scope', 'identifier_hash']);
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->char('token_hash', 64);
            $table->enum('request_source', ['user', 'admin'])->default('user');
            $table->unsignedInteger('initiated_by_user_id')->nullable();
            $table->string('delivery_channel', 20)->default('email');
            $table->string('reason', 500)->nullable();
            $table->char('requested_ip_hash', 64);
            $table->char('used_ip_hash', 64)->nullable();
            $table->dateTime('expires_at');
            $table->dateTime('used_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('initiated_by_user_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('password_reset_audit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('token_id')->nullable();
            $table->unsignedInteger('target_user_id')->nullable();
            $table->string('target_name', 255);
            $table->string('target_email', 255);
            $table->unsignedInteger('actor_user_id')->nullable();
            $table->string('actor_name', 255);
            $table->string('event_type', 50);
            $table->string('reason', 500)->nullable();
            $table->char('ip_hash', 64);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('token_id')->references('id')->on('password_reset_tokens')->nullOnDelete();
            $table->foreign('target_user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('actor_user_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('notification_type', 60);
            $table->string('title', 180);
            $table->string('message', 600);
            $table->string('target_url', 255)->nullable();
            $table->string('entity_type', 60)->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('dedupe_key', 160)->nullable();
            $table->dateTime('read_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
        Schema::dropIfExists('password_reset_audit');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('login_attempts');
    }
};