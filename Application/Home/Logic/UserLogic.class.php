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

    public function detail($uid) {
        $User= D ( 'User' );
        $user=$User->where(array('user_id'=>$uid))->find();
        return $user;
    }


}