<?php
namespace Basesvr\SimpleVideo\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UserVideoInfoEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var int
     */
    public $user_id;

    /**
     * @var string
     */
    public $action = 'create';

    /**
     * @var array
     */
    public $params = [];

    /**
     * UserVideoInfoEvent constructor.
     *
     * @param int $user_id
     * @param string $action
     * @param array $params
     */
    public function __construct(int $user_id, string $action = 'create', array $params = [])
    {
        $this->user_id = $user_id;
        $this->action = $action;
        $this->params = $params;
    }
}
