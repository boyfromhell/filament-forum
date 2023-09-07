<?php

namespace IchBin\FilamentForum\Commands;

use Illuminate\Console\Command;

class FilamentForumCommand extends Command
{
    public $signature = 'filament-forum';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
