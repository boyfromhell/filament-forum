<?php

namespace IchBin\FilamentForum\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \IchBin\FilamentForum\FilamentForum
 */
class FilamentForum extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \IchBin\FilamentForum\FilamentForum::class;
    }
}
