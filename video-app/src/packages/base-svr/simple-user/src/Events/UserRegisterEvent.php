<?php
namespace Basesvr\SimpleUser\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UserRegisterEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var int
     */
    public $user_id;

    /**
     * @var
     */
    public $action;

    /**
     * @var array
     */
    public $params = [];

    /**
     * UserRegisterEvent constructor.
     * @param int $user_id
     * @param string $action
     * @param array $params
     */
    public function __construct(int $user_id, string $action, array $params = [])
    {
        $this->user_id = $user_id;
        $this->action  = $action;
        $this->params = $params;
    }
}
