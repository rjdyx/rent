<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Rent;
use App\HouseholdMsg;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $householdmsgs = HouseholdMsg::all();
            date_default_timezone_set('PRC');
            $time = time();
//            $time = strtotime('2016-08-31 23:50:50');
            $days = date('t', $time);
            foreach ($householdmsgs as $householdmsg){
                \App\libraries\Util\calculateOneMonthRent($householdmsg, $time, $days);
            }
        })->everyMinute();

    }
}
