<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\FetchTmdbGenresJob;

class FetchTmdb extends Command
{
    protected $signature = 'tmdb:fetch';
    protected $description = 'Fetch 50 movies, 10 series, and all genres from TMDb API';

    public function handle()
    {
        $this->info("Dispatching jobs for genres, movies, and series...");

        FetchTmdbGenresJob::dispatch();
        $this->call('tmdb:sync-movies');
        $this->call('tmdb:sync-series');

        $this->info("Jobs dispatched!");
    }
}
