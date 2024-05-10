<?php

namespace App\Console;

use App\Console\Commands\OrderCron;
use App\Console\Commands\TrashFileCron;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\OrderCron::class,
        Commands\TrashFileCron::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(OrderCron::class)->everyMinute();
        $schedule->command(TrashFileCron::class)->everyMinute();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
