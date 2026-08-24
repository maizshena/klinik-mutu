<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wilayah', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('kode_wilayah', 20)->nullable();
            $table->string('nama_wilayah', 150);
            $table->string('nama_normalized', 160)->default('');
            $table->string('tipe', 50);
            $table->unsignedTinyInteger('level_wilayah')->default(0);
            $table->boolean('aktif')->default(true);
            $table->enum('routing_mode', ['otomatis', 'lokal', 'provinsi', 'pusat'])->default('otomatis');
            $table->string('catatan_layanan')->nullable();
            $table->unsignedInteger('layanan_updated_by')->nullable();
            $table->dateTime('layanan_updated_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('parent_id')->references('id')->on('wilayah')->nullOnDelete();
            $table->index(['tipe', 'level_wilayah']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wilayah');
    }
};