<?php

namespace Basesvr\SimpleUser\Listeners;

use Basesvr\SimpleUser\Events\UserRegisterEvent;
use Basesvr\SimpleUser\UserInvitation;

class UserInviteHistoryListener
{

    public function __construct()
    {
    }

    /**
     * @param UserRegisterEvent $event
     */
    public function handle(UserRegisterEvent $event)
    {

        if (isset($event->params['invite_code'])) {
            UserInvitation::getInstance()->addInviteHistory($event->user_id, $event->params['invite_code']);
        }


//        dd($event);
//        $event->params;
//        if (isset($event->params['invite_code'])) {
//
//        }
//        switch ($event->action) {
//            case "created" :
//                \Basesvr\SimpleUser\User::getInstance()->addHistory(['user_id' => auth()->guard('api')->user()->id,'video_id' => $event->video_id]);
//                break;
//        }
    }

}