<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\FetchTmdb;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        FetchTmdb::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('tmdb:fetch')->daily();
    }

    /**
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
