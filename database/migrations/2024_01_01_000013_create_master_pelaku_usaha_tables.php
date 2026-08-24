<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_pelaku_usaha', function (Blueprint $table) {
            $table->id();
            $table->string('source_system', 60)->default('Portal Data KKP');
            $table->string('source_business_id', 50)->nullable();
            $table->string('no_kusuka', 32);
            $table->string('nama_pelaku', 255);
            $table->string('nama_usaha', 255)->nullable();
            $table->string('bentuk_usaha', 100)->nullable();
            $table->string('provinsi_source_code', 20)->nullable();
            $table->unsignedInteger('provinsi_wilayah_id')->nullable();
            $table->string('provinsi_name', 150)->nullable();
            $table->string('kabupaten_source_code', 20)->nullable();
            $table->unsignedInteger('kabupaten_wilayah_id')->nullable();
            $table->string('kabupaten_name', 150)->nullable();
            $table->string('kecamatan_source_code', 20)->nullable();
            $table->unsignedInteger('kecamatan_wilayah_id')->nullable();
            $table->string('kecamatan_name', 150)->nullable();
            $table->text('alamat_pelaku')->nullable();
            $table->string('source_status', 30)->nullable();
            $table->string('data_status', 30)->default('aktif');
            $table->string('verification_status', 40)->default('terverifikasi_sumber');
            $table->unsignedInteger('source_row_count')->default(1);
            $table->unsignedInteger('unit_count_source')->default(0);
            $table->text('source_notes')->nullable();
            $table->unsignedBigInteger('linked_profile_id')->nullable();
            $table->unsignedBigInteger('imported_batch_id')->nullable();
            $table->dateTime('imported_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('provinsi_wilayah_id')->references('id')->on('wilayah')->nullOnDelete();
            $table->foreign('kabupaten_wilayah_id')->references('id')->on('wilayah')->nullOnDelete();
            $table->foreign('kecamatan_wilayah_id')->references('id')->on('wilayah')->nullOnDelete();
            $table->foreign('linked_profile_id')->references('id')->on('pelaku_usaha_profiles')->nullOnDelete();
            $table->foreign('imported_batch_id')->references('id')->on('pelaku_usaha_import_batches')->nullOnDelete();
            $table->unique('no_kusuka');
        });

        Schema::create('master_pelaku_usaha_units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_pelaku_id');
            $table->string('source_system', 60)->default('Portal Data KKP');
            $table->string('source_detail_id', 50);
            $table->string('source_facility_id', 50)->nullable();
            $table->string('nama_unit_usaha', 255)->nullable();
            $table->string('kode_skala_usaha', 30)->nullable();
            $table->string('skala_usaha', 80)->nullable();
            $table->decimal('omzet_tahunan', 20, 2)->nullable();
            $table->string('kode_produk_olahan', 50)->nullable();
            $table->string('produk_olahan', 255)->nullable();
            $table->string('provinsi_source_code', 20)->nullable();
            $table->unsignedInteger('provinsi_wilayah_id')->nullable();
            $table->string('provinsi_name', 150)->nullable();
            $table->string('kabupaten_source_code', 20)->nullable();
            $table->unsignedInteger('kabupaten_wilayah_id')->nullable();
            $table->string('kabupaten_name', 150)->nullable();
            $table->string('kecamatan_source_code', 20)->nullable();
            $table->unsignedInteger('kecamatan_wilayah_id')->nullable();
            $table->string('kecamatan_name', 150)->nullable();
            $table->string('kelurahan_source_code', 20)->nullable();
            $table->unsignedInteger('kelurahan_wilayah_id')->nullable();
            $table->string('kelurahan_name', 150)->nullable();
            $table->text('alamat_unit')->nullable();
            $table->string('source_status', 30)->nullable();
            $table->string('location_source', 30)->default('SARPRAS');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('location_accuracy', 30)->default('wilayah_administratif');
            $table->string('verification_status', 40)->default('terverifikasi_sumber');
            $table->unsignedBigInteger('imported_batch_id')->nullable();
            $table->dateTime('imported_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('master_pelaku_id')->references('id')->on('master_pelaku_usaha')->cascadeOnDelete();
            $table->foreign('provinsi_wilayah_id')->references('id')->on('wilayah')->nullOnDelete();
            $table->foreign('kabupaten_wilayah_id')->references('id')->on('wilayah')->nullOnDelete();
            $table->foreign('kecamatan_wilayah_id')->references('id')->on('wilayah')->nullOnDelete();
            $table->foreign('kelurahan_wilayah_id')->references('id')->on('wilayah')->nullOnDelete();
            $table->foreign('imported_batch_id')->references('id')->on('pelaku_usaha_import_batches')->nullOnDelete();
        });

        Schema::create('pelaku_usaha_import_staging', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_id');
            $table->unsignedInteger('row_number');
            $table->string('record_type', 20);
            $table->string('no_kusuka', 32)->nullable();
            $table->string('source_business_id', 50)->nullable();
            $table->string('source_unit_id', 50)->nullable();
            $table->longText('payload_json');
            $table->string('stage_status', 30)->default('diterima');
            $table->string('error_message', 500)->nullable();
            $table->unsignedBigInteger('master_id')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('batch_id')->references('id')->on('pelaku_usaha_import_batches')->cascadeOnDelete();
            $table->foreign('master_id')->references('id')->on('master_pelaku_usaha')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelaku_usaha_import_staging');
        Schema::dropIfExists('master_pelaku_usaha_units');
        Schema::dropIfExists('master_pelaku_usaha');
    }
};