<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DBServiceProvider extends ServiceProvider
{

    /**
     *
     * boot
     *
     */
    public function boot()
    {
        if (config('sys.db_log_listen')) {
            \DB::listen(function($query) {
                $log = $this->format($query);
//                $filename = storage_path().'/logs/db.log';
//                \Log::useDailyFiles($filename,1);
                \Log::info($log,$query->bindings);
            });
        }
    }

    /**
     *
     * register
     *
     */
    public function register()
    {
        //

    }

    /**
     *
     * format
     *
     * @param $query
     * @return mixed|string
     */
    protected function format ($query)
    {
        $log = str_replace('?', '"'.'%s'.'"', $query->sql);
//        $log = vsprintf($log, $query->bindings);
        //$log = str_replace("\\","",$log);
        return $log;
    }

}
