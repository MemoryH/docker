<?php
namespace App\Console\Commands;

use App\Models\UserExtendModel;
use Carbon\Carbon;
use Illuminate\Console\Command;


/**
 * Class ResetUserVideoViewCountCommand
 * @package App\Console\Commands
 */
class ResetUserVideoViewCountCommand extends Command
{

    protected $signature = 'video:resetUserVideoViewCount';

    protected $description = '重置用户的观影次数';

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
        UserExtendModel::getInstance()->where(['equity_daily_view_3_status' => 1])->where('equity_daily_view_3_until', '>', Carbon::now()->toDateTimeString() )->update(['equity_daily_view_3_count' => 3]);

        UserExtendModel::getInstance()->where(['equity_daily_view_10_status' => 1])->where('equity_daily_view_10_until', '>', Carbon::now()->toDateTimeString())->update(['equity_daily_view_10_count' => 10]);

    }

}
