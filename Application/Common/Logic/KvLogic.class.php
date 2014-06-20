<?php
/**
 * Created by Green Studio.
 * File: KvLogic.class.php
 * User: TianShuo
 * Date: 14-3-11
 * Time: 下午8:54
 */

namespace Common\Logic;

use Think\Model;

/**
 * Class KvLogic
 * @package Common\Logic
 */
class KvLogic extends Model
{

    /**
     * @return mixed
     */
    public function getAll()
    {
        $kvs = $this->cache(APP_Cache,2)->where(1)->select();

         return $kvs;
    }


}