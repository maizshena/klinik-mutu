<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KnowledgeArticle extends Model
{
    protected $table = 'knowledge_articles';
    protected $guarded = ['id'];

    protected $casts = [
        'published_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'archived_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(KnowledgeArticleAttachment::class, 'article_id');
    }

    public function history(): HasMany
    {
        return $this->hasMany(KnowledgeArticleHistory::class, 'article_id');
    }

    public function sources(): HasMany
    {
        return $this->hasMany(KnowledgeArticleSource::class, 'article_id');
    }
}