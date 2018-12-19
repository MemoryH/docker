<?php
/**
 * User: Third
 * Time: 上午10:32
 */

namespace Third\Dict\Facades;

use Illuminate\Support\Facades\Facade;
use Third\Dict\Contracts\DictInterface;

class Dict extends Facade {
    /**
     *
     * Get the registered name of the component.
     *
     * @return string
     *
     */
    protected static function getFacadeAccessor()
    {
//        return 'Receivable';
        return DictInterface::class;
    }
}
