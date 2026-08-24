<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('knowledge_articles', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 180)->unique();
            $table->string('article_type', 40)->default('faq');
            $table->string('category', 60)->default('lainnya');
            $table->string('title', 255);
            $table->text('summary');
            $table->longText('content');
            $table->string('keywords', 500)->nullable();
            $table->string('status', 30)->default('draft');
            $table->string('visibility', 20)->default('public');
            $table->unsignedInteger('anonymization_checked_by')->nullable();
            $table->dateTime('anonymization_checked_at')->nullable();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('reviewed_by')->nullable();
            $table->dateTime('reviewed_at')->nullable();
            $table->unsignedInteger('published_by')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->unsignedInteger('archived_by')->nullable();
            $table->dateTime('archived_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('anonymization_checked_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('published_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('archived_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('knowledge_article_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('article_id');
            $table->unsignedInteger('uploaded_by');
            $table->string('original_name', 255);
            $table->string('stored_name', 120);
            $table->string('mime_type', 150);
            $table->string('file_extension', 20);
            $table->unsignedInteger('file_size');
            $table->char('sha256', 64);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('article_id')->references('id')->on('knowledge_articles')->cascadeOnDelete();
            $table->foreign('uploaded_by')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::create('knowledge_article_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('article_id');
            $table->unsignedInteger('actor_user_id')->nullable();
            $table->string('actor_name', 255);
            $table->string('action_name', 60);
            $table->string('from_status', 30)->nullable();
            $table->string('to_status', 30);
            $table->text('note')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('article_id')->references('id')->on('knowledge_articles')->cascadeOnDelete();
            $table->foreign('actor_user_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('knowledge_article_sources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('article_id');
            $table->string('source_type', 40);
            $table->unsignedBigInteger('source_id');
            $table->string('source_reference', 80)->nullable();
            $table->string('source_title', 255)->nullable();
            $table->longText('snapshot_problem')->nullable();
            $table->longText('snapshot_answer')->nullable();
            $table->unsignedInteger('created_by');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('article_id')->references('id')->on('knowledge_articles')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_article_sources');
        Schema::dropIfExists('knowledge_article_history');
        Schema::dropIfExists('knowledge_article_attachments');
        Schema::dropIfExists('knowledge_articles');
    }
};