<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/7 0007
 * Time: 16:14
 */

namespace Basesvr\SimpleVideo;

use App\Models\VideoCommentModel;
use App\User as UserModel;

class VideoComment extends Base
{

    public function __construct()
    {
    }

    /**
     *
     * lists
     *
     * @param array $search
     * @param array $orders
     * @param $columns
     * @param int $page
     * @param int $limit
     * @return mixed
     * @throws \Exception
     */
    public function lists (array $search, array $orders, $columns, int $page, int $limit)
    {
        $videoCommentTable = VideoCommentModel::getInstance()->getTable();
        $userTable = UserModel::getInstance()->getTable();

        if (!isset($search['video_id'])) throw new \Exception('video_id is invalid.');

        $query = VideoCommentModel::getInstance()->where('video_id', $search['video_id'])
            ->rightJoin("$userTable as u",'u.id','=', "$videoCommentTable.user_id")
            ->select($videoCommentTable.'.*','u.name','u.email','u.cover_path');

        $query = $this->order($orders, $query);

        return $query->paginate($limit, $columns, 'page', $page);
    }

    /**
     *
     * info
     *
     * @param int $id
     * @param array $params
     * @return mixed
     */
    public function info (int $id, array $params)
    {

    }

    /**
     *
     * add
     *
     * @param array $params
     * @return mixed
     */
    public function add (array $params)
    {
        $params['user_id'] = auth()->user()->id;
        $ret = VideoCommentModel::getInstance()->create($params)->toArray();
        $user = UserModel::getInstance()->where('id',$params['user_id'])->select('name','id as user_id','email','cover_path')->first()->toArray();
        $ret += $user;
        return $ret;
    }
}