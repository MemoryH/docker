<?php

namespace Basesvr\SimpleUser\Listeners;

use Basesvr\SimpleUser\Events\UserRegisterEvent;
use Basesvr\SimpleUser\UserEmpirical;

class UserEmpiricalHistoryListener
{

    public function __construct()
    {
    }

    /**
     * @param UserRegisterEvent $event
     */
    public function handle(UserRegisterEvent $event)
    {
        UserEmpirical::getInstance()->addHistory($event->user_id, $event->params['empirical_event']);
    }

}