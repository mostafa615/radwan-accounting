<?php

namespace App\Console;

use App\Console\Commands\DailyAttend;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\toOperate;
use App\Console\Commands\toReoperate;
use App\Console\Commands\UpdateTransactions;
use App\Models\AttendanceSettings;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        toOperate::class,
        toReoperate::class,
        UpdateTransactions::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('cron')->everyMinute();
        $schedule->command(DailyAttend::class)->everyMinute()->withoutOverlapping();

        $schedule->command(toOperate::class)->everyMinute()->withoutOverlapping();
        $schedule->command(toReoperate::class)->everyMinute()->withoutOverlapping();

        

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
