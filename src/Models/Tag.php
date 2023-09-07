<?php

namespace IchBin\FilamentForum\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'forum_tags';

    protected $fillable = [
        'parent_id', 'name', 'color', 'icon', 'description',
    ];

    /**
     * Get the parent forum of this forum.
     */
    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function discussions(): BelongsToMany
    {
        return $this->belongsToMany(Discussion::class, 'forum_discussion_tags', 'tag_id', 'discussion_id');
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('order');
    }

    public function scopeParents(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }
}
