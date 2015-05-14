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
     * @param $uid int 用户UID
     * @param bool $relation 是否关联查询
     *
     * @return mixed 找到返回数组
     */
    public function detail($uid, $relation = true)
    {
        $user = D('User')->where(array('user_id' => $uid))->relation($relation)->find();
        return $user;
    }

    public function detailByUserlogin($user_login, $relation = true)
    {
        $user = D('User')->where(array('user_login' => $user_login))->relation($relation)->find();
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
     * 改变用户信息
     * @param int $uid
     * @param array $data
     * @return array
     */
    public function update($uid = 0, $data = array())
    {
        $db_res = D('User')->where(array("user_id" => $uid))->data($data)->save();
        if ($db_res) {
            return arrayRes(1, "用户信息修改成功");
        } else {
            return arrayRes(0, "用户信息修改失败");
        }
    }

    /**
     * 添加用户
     * @param $user
     * @return array
     */
    public function addUser($user)
    {
        if ($new_user_id = D('User')->add($user)) {

            $role = array(
                'role_id' => $user['user_level'],
                'user_id' => $new_user_id
            );
            if (D('Role_users')->add($role)) {
                return arrayRes(1, '添加成功！', U('Admin/Access/index'));
            } else {
                return arrayRes(0, '添加用户权限失败！', U('Admin/Access/index'));
            }
        } else {
            return arrayRes(0, '添加用户失败！', U('Admin/Access/index'));
        }
    }


    /**
     * 改变用户密码
     * @param $uid
     * @param $oldPassword
     * @param $newPassword
     * @return string
     */
    public function changePassword($uid, $oldPassword, $newPassword)
    {

        $user = $this->detail($uid);
        if ($user['user_pass'] != encrypt($oldPassword)) {
            return arrayRes(0, "原用户密码不正确");
        }

        $data['user_id'] = $uid;
        $data['user_pass'] = encrypt($newPassword);

        if (D('User')->where(array("user_id" => $uid))->data($data)->save()) {
            return arrayRes(1, "密码修改成功", U("Admin/login/logout"));
        } else {
            return arrayRes(0, "密码修改失败");
        }

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

    public function selectWithPostsCount($limit = 0, $relation = false, $where = array(), $order = '')
    {
        return D('User')->where($where)->limit($limit)->field(GreenCMS_DB_PREFIX . 'user.*,count( ' . GreenCMS_DB_PREFIX . 'posts.post_id) as post_count')
            ->join('LEFT JOIN  ' . GreenCMS_DB_PREFIX . 'posts ON ' . GreenCMS_DB_PREFIX .
                'posts.user_id = ' . GreenCMS_DB_PREFIX . 'user.user_id')
            ->group(GreenCMS_DB_PREFIX . 'user.user_id')->relation($relation)->select();

    }


    //TODO 使用原生SQL提高效率
    public function getCatAccess($uid)
    {
        $user = $this->detail($uid);
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