<?php

namespace Basesvr\SimpleVideo\Listeners;

use Basesvr\SimpleVideo\Events\UserHistoryEvent;

class UserHistoryListener
{

    public function __construct()
    {
    }

    /**
     *
     * Handle for event
     *
     * @param UserHistoryEvent $event
     */
    public function handle(UserHistoryEvent $event)
    {
        switch ($event->action) {
            case "created" :
                \Basesvr\SimpleUser\User::getInstance()->addHistory(['user_id' => auth()->guard('api')->user()->id,'video_id' => $event->video_id]);
                break;
        }
    }

}