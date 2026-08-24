<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KnowledgeArticleHistory extends Model
{
    protected $table = 'knowledge_article_history';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function article(): BelongsTo
    {
        return $this->belongsTo(KnowledgeArticle::class, 'article_id');
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }
}