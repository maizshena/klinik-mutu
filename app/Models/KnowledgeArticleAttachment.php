<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KnowledgeArticleAttachment extends Model
{
    protected $table = 'knowledge_article_attachments';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function article(): BelongsTo
    {
        return $this->belongsTo(KnowledgeArticle::class, 'article_id');
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}