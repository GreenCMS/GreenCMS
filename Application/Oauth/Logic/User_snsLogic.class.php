<?php
/**
 * Created by PhpStorm.
 * User: TianShuo
 * Date: 14-6-2
 * Time: 下午3:43
 */

namespace Oauth\Logic;
use Think\Model\RelationModel;
use Oauth\Model\User_snsModel;

class User_snsLogic extends RelationModel{


    public function detailByOID($id, $type,$relation = true, $info_with = array())
    {
        $info = $info_with;
        $info['openid'] = ($id);
        $info['type'] = ($type);

        $User_sns = new User_snsModel();
        $open_user_info = $User_sns->where($info)->relation($relation)->find();
        return $open_user_info;
    }

    public function detailByUID($id,$type, $relation = true, $info_with = array())
    {
        $info = $info_with;
        $info['user_id'] = ($id);
        $info['type'] = ($type);

        $User_sns = new User_snsModel();
        $open_user_info = $User_sns->where($info)->relation($relation)->find();
        return $open_user_info;
    }
} 