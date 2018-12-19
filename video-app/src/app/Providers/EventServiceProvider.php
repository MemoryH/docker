<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Basesvr\SimpleVideo\Events\UserHistoryEvent' => [
            'Basesvr\SimpleVideo\Listeners\UserHistoryListener',
        ],
        'Basesvr\SimpleUser\Events\UserRegisterEvent' => [
            'Basesvr\SimpleUser\Listeners\UserInviteCodeListener',
            'Basesvr\SimpleUser\Listeners\UserEmpiricalHistoryListener',
            'Basesvr\SimpleUser\Listeners\UserCoinsHistoryListener',
            'Basesvr\SimpleUser\Listeners\UserInviteHistoryListener',
        ],
        'Basesvr\SimpleUser\Events\UserLoginEvent' => [
            'Basesvr\SimpleUser\Listeners\UserLoginEmpiricalHistoryListener',
        ],
        'Basesvr\SimpleVideo\Events\UserVideoInfoEvent' => [
            'Basesvr\SimpleVideo\Listeners\UserVideoInfoListener',
        ],
        'Basesvr\SimpleUser\Events\UserVideoViewEvent' => [
            'Basesvr\SimpleUser\Listeners\UserVideoViewListener'
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
