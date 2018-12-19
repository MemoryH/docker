<?php
namespace App\Console\Commands;

use function foo\func;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Command;
use DB;

/**
 * Class AnalyzeVideoTypeCommand
 * @package App\Console\Commands
 */
class AnalyzeVideoTypeCommand extends Command
{

    protected $signature = 'analyze:video-type';

    protected $description = 'analyze: for video type';

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
        DB::select("SET @@group_concat_max_len = 15000;");
        DB::select("
insert into v_analyze_video_type (`global_type`,`type_id`,`video_ids`,`video_count`)
SELECT 
  vt.global_type,vt.type_id, GROUP_CONCAT(vt.video_id) as video_ids, COUNT(1) as video_count
from v_video_type as vt
GROUP BY vt.global_type,vt.type_id
ON DUPLICATE KEY UPDATE video_ids=VALUES(video_ids),video_count=VALUES(video_count)
");
        $this->info("update ok.");
    }

}
