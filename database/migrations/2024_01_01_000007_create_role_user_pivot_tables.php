<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('permission_id');
            $table->primary(['role_id', 'permission_id']);

            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
            $table->foreign('permission_id')->references('id')->on('permissions')->cascadeOnDelete();
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('role_id');
            $table->boolean('is_primary')->default(false);
            $table->timestamp('assigned_at')->useCurrent();
            $table->primary(['user_id', 'role_id']);

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
        });

        Schema::create('user_wilayah', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('wilayah_id');
            $table->boolean('is_primary')->default(false);
            $table->enum('access_type', ['utama', 'tambahan', 'sementara'])->default('utama');
            $table->timestamp('assigned_at')->useCurrent();
            $table->primary(['user_id', 'wilayah_id']);

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('wilayah_id')->references('id')->on('wilayah')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_wilayah');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('role_permissions');
    }
};