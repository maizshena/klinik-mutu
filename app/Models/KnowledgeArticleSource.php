<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KnowledgeArticleSource extends Model
{
    protected $table = 'knowledge_article_sources';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function article(): BelongsTo
    {
        return $this->belongsTo(KnowledgeArticle::class, 'article_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}