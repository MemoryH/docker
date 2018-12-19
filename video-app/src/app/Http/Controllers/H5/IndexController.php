<?php
namespace App\Http\Controllers\H5;

use Basesvr\SimpleVideo\Dict;

class IndexController extends Controller
{

    // 小说首页
    public function index()
    {
        dd(Dict::getGlobalType(),Dict::getFilmType(),Dict::getGlobalEpoch(),Dict::getFilmDist(),Dict::getTeleplayDist(),Dict::getTeleplayType(),Dict::getCartoonDist(),Dict::getVarietyDist());
        dd(Dict::get("global","bool"),Dict::get("video","global"),Dict::value("video","global","film"));
        dd();
    }

}