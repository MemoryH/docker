<?php

namespace Basesvr\SimpleUser\Listeners;

use Basesvr\SimpleUser\Events\UserLoginEvent;
use Basesvr\SimpleUser\UserEmpirical;

class UserLoginEmpiricalHistoryListener
{

    public function __construct()
    {
    }

    /**
     * @param UserLoginEvent $event
     */
    public function handle(UserLoginEvent $event)
    {
        UserEmpirical::getInstance()->addHistory($event->user_id, $event->params['empirical_event']);
    }

}