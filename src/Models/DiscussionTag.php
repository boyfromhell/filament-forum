<?php

namespace IchBin\FilamentForum\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscussionTag extends Model
{
    use HasFactory;

    protected $table = 'forum_discussion_tags';

    public $timestamps = false;

    protected $fillable = [
        'discussion_id', 'tag_id'
    ];

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class, 'tag_id', 'id');
    }

    public function discussion(): BelongsTo
    {
        return $this->belongsTo(Discussion::class, 'discussion_id', 'id');
    }
}
