<?php

namespace Robiokidenis\FilamentMultiTenancy\Commands;

use Illuminate\Console\Command;

class FilamentMultiTenancyCommand extends Command
{
    public $signature = 'filament-multi-tenancy';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
