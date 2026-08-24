<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kompetensi_master', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_kompetensi', 80);
            $table->string('nama_kompetensi', 180);
            $table->text('deskripsi')->nullable();
            $table->integer('urutan')->default(100);
            $table->boolean('aktif')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kompetensi_master');
    }
};