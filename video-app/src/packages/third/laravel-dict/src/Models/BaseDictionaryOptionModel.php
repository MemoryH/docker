<?php
/**
 *------------------------------------------------------
 * BaseDictionaryOptionModel.php
 *------------------------------------------------------
 *
 * @author    Third@foxmail.com
 * @date      2017/05/25 07:34
 * @version   V1.0
 *
 */

namespace Third\Dict\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseDictionaryOptionModel extends Model
{
    use SoftDeletes;
    /**
     * 数据表名
     */
    protected $table = "base_dictionary_option";
    /**
     * 主键
     */
    protected $primaryKey = "id";

    /**
     * 可以被集体附值的表的字段
     */
    protected $fillable = [
        'dictionary_table_code',
        'dictionary_code',
        'key',
        'value',
        'name',
        'input_code',
        'sort'
    ];

    /**
     * 获取自己某一个模块的数据
     *
     * @author jiangzhiheng
     * @param $tableCode
     * @param $code
     *
     * @return Collection
     */
    public function getModule($tableCode, $code)
    {
        return $this->where('dictionary_table_code', $tableCode)->where('dictionary_code', $code)->get();
    }
}