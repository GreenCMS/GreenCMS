<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: AccessLogic.class.php
 * User: Timothy Zhang
 * Date: 14-1-26
 * Time: 下午7:27
 */

namespace Admin\Logic;

use Common\Logic\UserLogic;
use Common\Util\Category;
use Think\Model\RelationModel;

/**
 * Class AccessLogic
 * @package Admin\Logic
 */
class AccessLogic extends RelationModel
{

    /**
     * +----------------------------------------------------------
     * 管理员列表
     * +----------------------------------------------------------
     */
    public function adminList($limit = 0)
    {
        $where = array("user_level" => array('neq', 5));
        $UserLogic = new UserLogic();

        $res = $UserLogic->selectWithPostsCount($limit, true, $where);
        foreach ($res as $k => $v) {
            $res [$k] ['user_status'] = $v ['user_status'] == 1 ? "启用" : "禁用";
        }

        return $res;
    }

    /**
     * +----------------------------------------------------------
     * 游客列表
     * +----------------------------------------------------------
     */
    public function guestList($limit = 0)
    {
        $where = array("user_level" => '5');

        $UserLogic = new UserLogic();

        $res = $UserLogic->selectWithPostsCount($limit, true, $where);
        foreach ($res as $k => $v) {
            $res [$k] ['user_status'] = $v ['user_status'] == 1 ? "启用" : "禁用";
        }

        return $res;
    }

    /**
     * @return mixed
     */
    public function nodeList()
    {
        $cat = new Category ('Node', array(
            'id',
            'pid',
            'title',
            'fullname'
        ));
        $temp = $cat->getList(); // 获取分类结构

        foreach ($temp as $key => $value) {

            if ($value['level'] = 1 && array_key_exists($value['name'], get_opinion('group_level_1'))) {
                $group_level_1 = get_opinion('group_level_1');
                $temp[$key]["remark"] = $group_level_1[$value['name']];
            } else if ($value['level'] = 2 && array_key_exists($value['name'], get_opinion('admin_level_2'))) {
                $admin_level_2 = get_opinion('admin_level_2');
                $temp[$key]["remark"] = $admin_level_2[$value['name']];

            }

        }


        $level = array(
            "1" => "项目（GROUP_NAME）",
            "2" => "模块(MODEL_NAME)",
            "3" => "操作（ACTION_NAME）"
        );
        foreach ($temp as $k => $v) {
            $temp [$k] ['statusTxt'] = $v ['status'] == 1 ? "启用" : "禁用";
            $temp [$k] ['chStatusTxt'] = $v ['status'] == 0 ? "启用" : "禁用";
            $temp [$k] ['level'] = $level [$v ['level']];
            $list [$v ['id']] = $temp [$k];
        }
        unset ($temp);
        return $list;
    }

    /**
     * @return mixed
     */
    public function roleList()
    {
        $M = M("Role");
        $list = $M->select();
        foreach ($list as $k => $v) {
            $list [$k] ['statusTxt'] = $v ['status'] == 1 ? "启用" : "禁用";
            $list [$k] ['chStatusTxt'] = $v ['status'] == 0 ? "启用" : "禁用";
        }
        return $list;
    }

    /**
     * @param string $op
     * @return array
     */
    public function opStatus($op = 'Node')
    {
        $M = M("$op");
        $datas ['id'] = ( int )$_GET ["id"];
        $datas ['status'] = $_GET ["status"] == 1 ? 0 : 1;
        if ($M->save($datas)) {
            return array(
                'status' => 1,
                'info' => "处理成功",
                'data' => array(
                    "status" => $datas ['status'],
                    "txt" => $datas ['status'] == 1 ? "禁用" : "启动"
                )
            );
        } else {
            return array(
                'status' => 0,
                'info' => "处理失败"
            );
        }
    }

    /**
     * @return array
     */
    public function editNode()
    {
        $M = M("Node");
        return $M->save($_POST) ? array(
            'status' => 1,
            'info' => '更新节点信息成功',
            'url' => U('Admin/Access/nodeList')
        ) : array(
            'status' => 0,
            'info' => '更新节点信息失败'
        );
    }

    /**
     * @return array
     */
    public function addNode()
    {
        $M = M("Node");
        return $M->add($_POST) ? array(
            'status' => 1,
            'info' => $_POST ['id'] . '添加节点信息成功',
            'url' => U('Admin/Access/nodeList')
        ) : array(
            'status' => 0,
            'info' => '添加节点信息失败'
        );
    }

    /**
     * +----------------------------------------------------------
     * 添加管理员
     * +----------------------------------------------------------
     */
    public function addAdmin()
    {

    }

    /**
     * +----------------------------------------------------------
     * 编辑管理员
     * +----------------------------------------------------------
     */
    public function editAdmin()
    {

        $User = D("User");
        if (!empty ($_POST ['password'])) {
            $data ['user_pass'] = encrypt($_POST ['password']);
        } else {
            unset ($_POST ['password']);
        }


        $user_id = $_POST ['user_id0'];
        $role_id = (int )$_POST ['role_id'];
        // $data['user_id'] = (int) $_POST['user_id0'];
        $data ['user_login'] = $_POST ['user_login'];

        $data ['user_email'] = $_POST ['user_email'];
        $data ['user_url'] = $_POST ['user_url'];

        $data ['user_nicename'] = $_POST ['user_nicename'];
        $data ['user_status'] = $_POST ['user_status'];
        $data ['user_intro'] = $_POST ['user_intro'];


        $roleStatus = M("Role_users")->where("`user_id`=" . $user_id)->save(array(
            'role_id' => $role_id
        ));


        $data ['user_level'] = $_POST ['role_id'];


        if ($User->where(array('user_id' => $user_id))->save($data)) {

            return $roleStatus == true ? array(
                'status' => 1,
                'info' => "成功更新",
                'url' => U("Admin/Access/index")
            ) : array(
                'status' => 1,
                'info' => "成功更新，但更改用户所属组未更新",
                'url' => U("Admin/Access/index")
            );
        } else {

            return $roleStatus == true ? array(
                'status' => 1,
                'info' => "用户未更新，但更改用户所属组更新成功",
                'url' => U("Admin/Access/index")
            ) : array(
                'status' => 0,
                'info' => "所属用户组未更新，请重试"
            );
        }
    }

    /**
     * @param $id
     */
    public function delAdmin($id = -1)
    {
    }

    /**
     * +----------------------------------------------------------
     * 添加管理员
     * +----------------------------------------------------------
     */
    public function editRole()
    {
        $M = M("Role");
        if ($M->save($_POST)) {
            return array(
                'status' => 1,
                'info' => "成功更新",
                'url' => U("Admin/Access/roleList")
            );
        } else {
            return array(
                'status' => 0,
                'info' => "更新失败，请重试"
            );
        }
    }

    /**
     * +----------------------------------------------------------
     * 添加管理员
     * +----------------------------------------------------------
     */
    public function addRole()
    {
        $M = M("Role");
        if ($M->add($_POST)) {
            return array(
                'status' => 1,
                'info' => "成功添加",
                'url' => U("Admin/Access/roleList")
            );
        } else {
            return array(
                'status' => 0,
                'info' => "添加失败，请重试"
            );
        }
    }

    /**
     * @return array
     */
    public function changeRole()
    {
        $Access = D("Access");
        $role_id = (int)I('post.id');
        $Access->where("role_id=" . $role_id)->delete();
        $data = $_POST ['data'];

        if (count($data) == 0) {
            return array(
                'status' => 1,
                'info' => "清除所有权限成功",
                'url' => U("Admin/Access/roleList")
            );
        }
        $datas = array();
        foreach ($data as $k => $v) {
            $tem = explode(":", $v);
            $datas [$k] ['role_id'] = $role_id;
            $datas [$k] ['node_id'] = $tem [0];
            $datas [$k] ['level'] = $tem [1];
            $datas [$k] ['pid'] = $tem [2];
        }
        if ($Access->addAll($datas)) {
            return array(
                'status' => 1,
                'info' => "设置成功",
                'url' => U("Admin/Access/roleList")
            );
        } else {
            return array(
                'status' => 0,
                'info' => "设置失败，请重试"
            );
        }
    }
}
