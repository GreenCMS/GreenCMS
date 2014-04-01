<?php
/**
 * Created by Green Studio.
 * File: UserLogic.class.php
 * User: TianShuo
 * Date: 14-1-16
 * Time: 上午12:51
 */

namespace Common\Logic;
use Think\Model\RelationModel;

/**
 * Class UserLogic
 * @package Home\Logic
 */
class UserLogic extends RelationModel
{

    /**
     * @param $uid 用户UID
     * @param bool $relation 是否关联查询
     *
     * @return mixed 找到返回数组
     */
    public function detail($uid, $relation = true)
    {
        $user = D('User')->where(array('user_id' => $uid))->relation($relation)->find();
        return $user;
    }

    /**
     * @param bool $limit limit
     * @param bool $relation 是否关联
     *
     * @return mixed 找到返回数组
     */
    public function getList($limit = true, $relation = true)
    {
        return D('User')->limit($limit)->relation($relation)->select();
    }


    /**
     * @param $authInfo
     * @return string
     */
    public function genHash(& $authInfo)
    {
        $User = D('User', 'Logic');

        $condition['user_id'] = $authInfo['user_id'];
        $session_code = encrypt($authInfo['user_id'] . $authInfo['user_pass'] . time());
        $User->where($condition)->setField('user_session', $session_code);

        return $session_code;
    }

}