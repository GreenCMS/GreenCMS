<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: UserLogic.class.php
 * User: Timothy Zhang
 * Date: 14-1-16
 * Time: 上午12:51
 */

namespace Common\Logic;
use Think\Model\RelationModel;

/**
 * 用户逻辑定义
 * Class UserLogic
 * @package Home\Logic
 */
class UserLogic extends RelationModel
{

    /**
     * 获取指定用户信息
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
     * 获取list
     * @param bool $limit sql limit
     * @param bool $relation 是否关联
     *
     * @return mixed 找到返回数组
     */
    public function getList($limit = true, $relation = true)
    {
        return D('User')->limit($limit)->relation($relation)->select();
    }


    /**
     * 生成新的Hash
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

    public function selectWithPostsCount($limit = 0, $relation = false,$where=array(),$order = '')
    {
        return D('User')->where($where)->limit($limit)->field(GreenCMS_DB_PREFIX .'user.*,count( ' . GreenCMS_DB_PREFIX . 'posts.post_id) as post_count')
            ->join('LEFT JOIN  ' . GreenCMS_DB_PREFIX . 'posts ON ' . GreenCMS_DB_PREFIX .
                'posts.user_id = ' . GreenCMS_DB_PREFIX . 'user.user_id')
            ->group(GreenCMS_DB_PREFIX . 'user.user_id')->relation($relation)->select();

    }


    //TODO 使用原生SQL提高效率
    public function getCatAccess($uid){
        $user =$this->detail($uid);
        $role_id = $user["user_role"] ["role_id"];
        $role = D('Role')->where(array('id' => $role_id))->find();
        $where['cat_id'] = array('in', json_decode($role ["cataccess"]));
        $cats = D('Cats', 'Logic')->where($where)->select();
        foreach ($cats as $key => $value) {
            $cats[$key]['cat_slug'] = $cats[$key]['cat_name'];
        }
        return $cats;

    }

}