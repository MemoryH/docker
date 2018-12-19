<?php
namespace Basesvr\SimpleVideo\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UserHistoryEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var int
     */
    public $video_id;

    /**
     * @var string
     */
    public $action = 'create';

    /**
     * @var array
     */
    public $param = [];

    /**
     * ShopOrderEvent constructor.
     *
     * @param int $video_id
     * @param string $action
     * @param array $param
     */
    public function __construct(int $video_id, string $action = 'create', array $param = [])
    {
        $this->video_id = $video_id;
        $this->action = $action;
        $this->param = $param;
    }
}
