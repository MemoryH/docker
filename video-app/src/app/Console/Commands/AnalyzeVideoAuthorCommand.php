<?php
namespace App\Console\Commands;

use function foo\func;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Command;
use DB;

/**
 * Class AnalyzeVideoAuthorCommand
 * @package App\Console\Commands
 */
class AnalyzeVideoAuthorCommand extends Command
{

    protected $signature = 'analyze:author';

    protected $description = 'analyze: for author';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * handle
     *
     */
    public function handle()
    {
        DB::select("
insert into v_analyze_video_author (`actor_id`,`video_ids`,`video_count`)
SELECT 
  va.actor_id, GROUP_CONCAT(va.video_id) as video_ids, COUNT(1) as video_count
from v_video_author as va
GROUP BY va.actor_id
ON DUPLICATE KEY UPDATE video_ids=VALUES(video_ids),video_count=VALUES(video_count);
;");
        $this->info("update ok.");
    }

}
