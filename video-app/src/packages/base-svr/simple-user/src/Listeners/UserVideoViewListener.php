<?php

namespace Basesvr\SimpleUser\Listeners;


use App\Models\UserExtendModel;
use Basesvr\SimpleCommon\Config;
use Basesvr\SimpleCommon\Enums\ConfigEnum;
use Basesvr\SimpleUser\Events\UserVideoViewEvent;
use Basesvr\SimpleUser\User;
use Carbon\Carbon;

class UserVideoViewListener
{

    public function __construct()
    {
    }

    /**
     *
     * Handle for event
     *
     * @param UserVideoViewEvent $event
     */
    public function handle(UserVideoViewEvent $event)
    {
        User::getInstance()->checkVideoView();

    }

}