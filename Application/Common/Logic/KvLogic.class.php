<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: KvLogic.class.php
 * User: Timothy Zhang
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
        $kvs = $this->where(1)->select();

        return $kvs;
    }


}