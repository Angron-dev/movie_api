<?php

namespace App\Console\Commands;

use App\Jobs\FetchPopularMoviesJob;
use App\Jobs\FetchPopularSeriesJob;
use Illuminate\Console\Command;

class SyncSeriesCommand extends Command
{
    protected $signature = 'tmdb:sync-series';

    protected $description = 'Sync series from TMDB';

    public function handle(): void
    {
        FetchPopularSeriesJob::dispatch();

        $this->info('Series sync started');
    }
}
