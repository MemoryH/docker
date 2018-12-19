<?php

namespace Basesvr\SimpleVideo\Listeners;

use Basesvr\SimpleVideo\Events\UserVideoInfoEvent;
use Basesvr\SimpleUser\UserEmpirical;

class UserVideoInfoListener
{

    public function __construct()
    {
    }

    /**
     *
     * Handle for event
     *
     * @param UserVideoInfoEvent $event
     */
    public function handle(UserVideoInfoEvent $event)
    {
        UserEmpirical::getInstance()->addHistory($event->user_id, $event->params['empirical_event']);
    }

}