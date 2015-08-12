<?php
/**
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-6-2
 * Time: 下午3:43
 */

namespace Oauth\Logic;

use Think\Model\RelationModel;
use Oauth\Model\User_snsModel;

class User_snsLogic extends RelationModel
{


    public function detailByOID($id, $type, $info_with = array())
    {
        $info = $info_with;
        $info['openid'] = ($id);
        $info['type'] = strtoupper($type);

        $User_sns = new User_snsModel();
        $open_user_info = $User_sns->where($info)->relation(true)->find();
        return $open_user_info;
    }

    public function detailByUID($id, $type, $info_with = array())
    {
        $info = $info_with;
        $info['user_id'] = ($id);
        $info['type'] = strtoupper($type);

        $User_sns = new User_snsModel();
        $open_user_info = $User_sns->where($info)->relation(true)->find();
        return $open_user_info;
    }

    public function listByUID($id, $info_with = array())
    {
        $info = $info_with;
        $info['user_id'] = ($id);

        $User_sns = new User_snsModel();
        $open_user_info = $User_sns->where($info)->relation(true)->find();
        return $open_user_info;
    }


} 