<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelaku_usaha_import_batches', function (Blueprint $table) {
            $table->id();
            $table->string('import_type', 20);
            $table->string('source_file', 255);
            $table->char('source_sha256', 64);
            $table->string('status', 30)->default('diproses');
            $table->unsignedInteger('total_rows')->default(0);
            $table->unsignedInteger('staged_rows')->default(0);
            $table->unsignedInteger('promoted_rows')->default(0);
            $table->unsignedInteger('rejected_rows')->default(0);
            $table->unsignedInteger('started_by')->nullable();
            $table->dateTime('started_at')->useCurrent();
            $table->dateTime('completed_at')->nullable();
            $table->text('notes')->nullable();

            $table->foreign('started_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelaku_usaha_import_batches');
    }
};