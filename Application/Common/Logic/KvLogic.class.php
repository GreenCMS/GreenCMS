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

class KvLogic extends Model
{

    public function getAll()
    {
        $kvs = $this->where(1)->select();

         return $kvs;
    }


}