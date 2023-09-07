<?php

namespace IchBin\FilamentForum;

use Filament\Contracts\Plugin;
use Filament\Panel;
use IchBin\FilamentForum\Filament\Pages\ForumSettings;
use IchBin\FilamentForum\Filament\Resources\TagResource;

class FilamentForumPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-forum';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                TagResource::class,
            ])
            ->pages([
                ForumSettings::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
