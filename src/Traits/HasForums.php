<?php

namespace IchBin\FilamentForum\Traits;

use IchBin\FilamentForum\Models\Comment;
use IchBin\FilamentForum\Models\Discussion;
use IchBin\FilamentForum\Models\DiscussionVisit;
use IchBin\FilamentForum\Models\Like;
use IchBin\FilamentForum\Models\Notification;
use IchBin\FilamentForum\Models\Reply;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasForums
{
    public function appNotifications(): BelongsToMany
    {
        return $this->belongsToMany(Notification::class, 'forum_user_notifications', 'user_id', 'notification_id')->withPivot(['via_web', 'via_email']);
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(Discussion::class, 'user_id', 'id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class, 'user_id', 'id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class, 'user_id', 'id');
    }

    public function followings(): BelongsToMany
    {
        return $this->belongsToMany(Discussion::class, 'forum_followers', 'user_id', 'discussion_id')->withPivot('type');
    }

    public function discussionVisits(): HasMany
    {
        return $this->hasMany(DiscussionVisit::class, 'user_id', 'id');
    }

    public function discussionsTotalViews(): Attribute
    {
        return new Attribute(
            get: fn () => $this->discussions()->sum('visits')
        );
    }

    public function discussionsTotalUniqueViews(): Attribute
    {
        return new Attribute(
            get: fn () => $this->discussions()->sum('unique_visits')
        );
    }

    public function lastActivity(): Attribute
    {
        return new Attribute(
            get: fn () => $this->discussions
                ->merge($this->replies)
                ->merge($this->comments)
                ->merge($this->likes)
                ->sortByDesc('updated_at')
                ->first()?->updated_at ?? $this->updated_at
        );
    }

    public function hasNotification(string $notification, bool $isWeb, bool $isMail): bool
    {
        $query = $this->appNotifications()->where('name', $notification);
        if ($isWeb) {
            $query->where('via_web', true);
        }
        if ($isMail) {
            $query->where('via_email', true);
        }

        return $query->count() > 0;
    }
}
