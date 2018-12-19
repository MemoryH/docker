<?php

namespace Basesvr\SimpleUser\Listeners;

use Basesvr\SimpleUser\Events\UserRegisterEvent;
use Basesvr\SimpleUser\UserInvitation;

class UserInviteCodeListener
{

    public function __construct()
    {
    }

    /**
     * @param UserRegisterEvent $event
     */
    public function handle(UserRegisterEvent $event)
    {
        UserInvitation::getInstance()->storeInviteCode([
            'user_id' => $event->user_id,
        ]);
    }

}