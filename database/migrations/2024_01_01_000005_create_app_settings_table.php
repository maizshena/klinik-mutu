<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->string('setting_key', 100)->primary();
            $table->string('setting_value', 255);
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('content_pages', function (Blueprint $table) {
            $table->string('slug', 100)->primary();
            $table->string('title', 255);
            $table->text('summary');
            $table->longText('content');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_pages');
        Schema::dropIfExists('app_settings');
    }
};