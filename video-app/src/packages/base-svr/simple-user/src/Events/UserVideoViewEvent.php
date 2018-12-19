<?php
namespace Basesvr\SimpleUser\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UserVideoViewEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public function __construct()
    {
    }
}
