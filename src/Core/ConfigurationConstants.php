<?php

namespace IchBin\FilamentForum\Core;

use IchBin\FilamentForum\Models\Configuration;

enum ConfigurationConstants
{
    public static function case(string $name): bool
    {
        return Configuration::query()->where('name', $name)->first()?->is_enabled ?? false;
    }
}
