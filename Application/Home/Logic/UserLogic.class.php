<?php
/**
 * Created by Green Studio.
 * File: UserLogic.class.php
 * User: TianShuo
 * Date: 14-1-16
 * Time: 上午12:51
 */

namespace Home\Logic;
use \Think\Model\RelationModel;

class UserLogic extends RelationModel{

    public function detail($uid,$relation=true) {

        $user=D('User')->where(array('user_id'=>$uid))->relation($relation)->find();
        return $user;
    }


}