<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembina_mutu_master', function (Blueprint $table) {
            $table->id();
            $table->char('identity_key', 64);
            $table->string('nama_lengkap', 255);
            $table->string('nama_normalized', 255);
            $table->string('unit_kerja_sk', 255);
            $table->enum('tingkat', ['pusat', 'provinsi', 'kabupaten']);
            $table->enum('status', ['aktif', 'perlu_pemeriksaan', 'nonaktif', 'kedaluwarsa'])->default('aktif');
            $table->text('review_note')->nullable();
            $table->unsignedInteger('linked_user_id')->nullable();
            $table->string('source_document', 255);
            $table->date('valid_from');
            $table->date('valid_until');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('linked_user_id')->references('id')->on('users')->nullOnDelete();
            $table->unique('identity_key');
        });

        Schema::create('pembina_mutu_nomor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pembina_id');
            $table->string('nomor_terdaftar', 50);
            $table->enum('kategori_sk', ['haccp', 'kelayakan_pengolahan']);
            $table->enum('kelompok_sk', ['pusat', 'daerah']);
            $table->unsignedSmallInteger('source_page')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('pembina_id')->references('id')->on('pembina_mutu_master')->cascadeOnDelete();
        });

        Schema::create('pembina_mutu_wilayah', function (Blueprint $table) {
            $table->unsignedBigInteger('pembina_id');
            $table->unsignedInteger('wilayah_id');
            $table->boolean('is_primary')->default(false);
            $table->string('source_text', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->primary(['pembina_id', 'wilayah_id']);

            $table->foreign('pembina_id')->references('id')->on('pembina_mutu_master')->cascadeOnDelete();
            $table->foreign('wilayah_id')->references('id')->on('wilayah')->cascadeOnDelete();
        });

        Schema::create('pembina_mutu_kompetensi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pembina_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('kompetensi_id');
            $table->string('kompetensi_lainnya', 500)->nullable();
            $table->enum('status', ['diajukan', 'terverifikasi', 'perlu_perbaikan', 'ditolak'])->default('diajukan');
            $table->dateTime('submitted_at');
            $table->unsignedInteger('reviewed_by')->nullable();
            $table->dateTime('reviewed_at')->nullable();
            $table->text('catatan_verifikator')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('pembina_id')->references('id')->on('pembina_mutu_master')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('kompetensi_id')->references('id')->on('kompetensi_master')->cascadeOnDelete();
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembina_mutu_kompetensi');
        Schema::dropIfExists('pembina_mutu_wilayah');
        Schema::dropIfExists('pembina_mutu_nomor');
        Schema::dropIfExists('pembina_mutu_master');
    }
};