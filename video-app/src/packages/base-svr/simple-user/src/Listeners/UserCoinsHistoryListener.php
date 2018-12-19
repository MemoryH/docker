<?php

namespace Basesvr\SimpleUser\Listeners;

use Basesvr\SimpleUser\Events\UserRegisterEvent;
use Basesvr\SimpleUser\UserCoins;

class UserCoinsHistoryListener
{

    public function __construct()
    {
    }

    /**
     * @param UserRegisterEvent $event
     */
    public function handle(UserRegisterEvent $event)
    {
        UserCoins::getInstance()->addHistory($event->user_id, $event->params['coins_event']);
    }

}