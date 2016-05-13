<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //日志记录每次执行的sql语句
//         DB::listen(function($sql, $bindings, $time) {
//             $time = date('y-m-d h:i:s',time());
//             Log::info('时间：'.$time.'  sql:'.$sql);
//         });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
