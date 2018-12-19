<?php

namespace Basesvr\SimpleCommon;

use App\Models\ActorModel;
use App\Models\ConfigModel;
use App\Models\ResourceModel;
use DB;

class Config
{

    private static $_instance;

    private $cacheKey = 'laravel-simple-common#';

    /**
     *
     * getInstance
     *
     * @return Config
     * @author Stevin.John
     */
    public static function getInstance ()
    {
        if (empty(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     *
     * getConfig
     *
     * @param string|null $key
     * @param null $default
     * @return null|string
     */
    public function getConfig(string $key=null, $default = null) {
       $config = \Cache::rememberForever($this->cacheKey, function()
       {
           return ConfigModel::getInstance()->pluck( 'value','key');
       });

       if($key){
           return $config[$key] ?? $default ?? '';
       }else{
           return $config;
       }
    }

    /**
     *
     * saveConfig
     *
     * @param $data
     * @return bool
     */
    public function saveConfig($data) {
//        $obj = new Model();
//        foreach($data as $key=>$val) {
//            $ok = $obj->updateOrCreate(['key'=>$key],['key' => $key,'value'=>$val]);
//        }
//
//        $config = $obj->pluck('value', 'key')->toArray();
//        \Cache::forever($this->cacheKey, $config);
        return true;
    }

    /**
     *
     * getConfigByActor
     *
     * @param $key
     * @param null $default
     * @return null|string
     */
    public function getConfigByActor ($key, $default = null)
    {
        $config = \Cache::rememberForever($this->cacheKey . 'actor#' . $key, function() {
            return ActorModel::getInstance()->pluck('name', 'id');
        });
        if($key){
            return $config[$key] ?? $default ?? '';
        }else{
            return $config;
        }
    }

    /**
     *
     * getConfigByActor
     *
     * @param $key
     * @param null $default
     * @return null|string
     */
    public function getConfigByResource ($key = null, $default = null)
    {
        $config = \Cache::rememberForever($this->cacheKey . 'resource#' . $key, function() {
            $ret = collect(ResourceModel::getInstance()->get())->keyBy('id'); //todo
            return $ret;
        });
        if($key){
            return $config[$key] ?? $default ?? '';
        }else{
            return $config;
        }
    }

}
