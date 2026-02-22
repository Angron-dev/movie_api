<?php

namespace App\Console\Commands;

use App\Jobs\FetchPopularMoviesJob;
use Illuminate\Console\Command;

class SyncMoviesCommand extends Command
{
    protected $signature = 'tmdb:sync-movies';

    protected $description = 'Sync movies from TMDB';

    public function handle(): void
    {
        FetchPopularMoviesJob::dispatch();

        $this->info('Movie sync started');
    }
}
