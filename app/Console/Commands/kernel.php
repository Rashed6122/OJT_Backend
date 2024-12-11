<?php

namespace App\Console;

use App\Jobs\FetchRandomUserData;  
use App\Console\Commands\ForceDeleteOldPosts; 
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        ForceDeleteOldPosts::class,  
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command(ForceDeleteOldPosts::class)->daily();

        $schedule->job(new FetchRandomUserData())->everySixHours();  
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}