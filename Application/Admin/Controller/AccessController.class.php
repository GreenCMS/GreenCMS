<?php
/**
 * Created by Green Studio.
 * File: AccessController.class.php
 * User: TianShuo
 * Date: 14-1-26
 * Time: 下午5:26
 */

namespace Admin\Controller;


use Common\Util\Category;

class AccessController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->checkToken();
    }

    // 用户列表
    public function index()
    {
        $this->listname = '管理组用户';
        $list = D('Access', 'Logic')->adminList();
        $this->assign('list', $list);
        $this->display('userlist');
    }

    // 游客列表
    public function guest()
    {
        $this->listname = '游客用户';
        $this->list = D('Access', 'Logic')->guestList();
        $this->display('userlist');
    }

    // 用户锁定处理
    public function indexlocked()
    {
        $id = $_GET ['id'];
        // 锁定用户
        if ($id == 1) { // 判断用户是否为超级管理员，如果是不能做任何操作（我的超级管理员ID为1）
            $this->error('对不起，您不能对此用户做任何操作！');
        } else {
            $i = M('User')->where(array(
                'id' => $id
            ))->setField('lock', '1');
            $this->success('用户已关闭！');
        }
    }

    // 用户解锁处理
    public function indexlock($id)
    {
        // 解锁用户
        if ($id == 1) { // 判断用户是否为超级管理员，如果是不能做任何操作（我的超级管理员ID为1）
            $this->error('对不起，您不能对此用户做任何操作！');
        } else {
            $i = M('User')->where(array(
                'id' => $id
            ))->setField('lock', '0');
            $this->success('用户已开启！');
        }
    }


    // 角色列表
    public function rolelist()
    {
        $this->assign('rolelist', D('Access', 'Logic')->roleList());
        $this->display();
    }


    // 节点列表
    public function nodelist()
    {
        $field = array(
            'id',
            'name',
            'title',
            'pid'
        );
        // $node = M('node')->field($field)->order('sort')->select();
        $node = D('Access', 'Logic')->nodeList();
        // $node=node_merge($node);
        // print_r($node);die;
        $this->assign('node', $node);
        $this->display();
    }


    // 添加用户
    public function addUser()
    {
        $this->action_name = 'addUser';
        $role = M('role')->select();
        $this->assign('info', $this->getRoleListOption());
        // $this->assign("info",$this->getRoleListOption($info) );
        $this->display("adduser");
    }

    // 添加用户表单接受
    public function addUserHandle()
    {
        $w = htmlspecialchars(trim($_POST ['user_login']));
        $i = D('user')->where(array(
            'user_login' => $w
        ))->select();
        if ($i != '') {
            $this->error('用户名已存在！');
        } else {
            // 组合用户信息并添加

            $user = array(
                'user_login'    => htmlspecialchars(trim($_POST ['user_login'])),
                'user_nicename' => htmlspecialchars(trim($_POST ['user_nicename'])),
                'user_pass'     => encrypt($_POST ['password']),
                'user_email'    => htmlspecialchars($_POST ['user_email']),
                'user_url'      => htmlspecialchars($_POST ['user_url']),
                'user_intro'    => htmlspecialchars($_POST ['user_intro']),
                'user_status'   => htmlspecialchars($_POST ['user_status']),

                // 'logintime'=>time(),
                // 'loginip'=>get_client_ip(),
                // 'lock'=>$_POST['lock']
            );
            // 添加用户与角色关系

            $user ['user_level'] = $_POST ['role_id'];

            $User = D('User');
            $User_users = D('Role_users');
            if ($new_id = $User->add($user)) {

                $role = array(
                    'role_id' => $_POST ['role_id'],
                    'user_id' => $new_id
                );
                if ($User_users->add($role)) {
                    $this->success('添加成功！', U('Admin/Access/index'));
                } else {
                    $this->error('添加用户权限失败！', U('Admin/Access/index'));
                }
            } else {
                //    die(D('User')->getlastsql());
                $this->error('添加用户失败！', U('Admin/Access/index'));
            }
        }
    }

    /**
     * +----------------------------------------------------------
     * 编辑用户
     * +----------------------------------------------------------
     */
    public function editUser($aid = 1)
    {
        if (IS_POST) {
            header('Content-Type:application/json; charset=utf-8');
            $res = (D('Access', 'Logic')->editAdmin());
            if ($res ['status'] == 1) {
                $this->success($res ['info'], U('Admin/Access/index'));
            } elseif ($res ['status'] == 0) {
                $this->error($res ['info'], U('Admin/Access/index'));
            } else {
                $this->error('系统错误请稍候再试', U('Admin/Access/index'));
            }
        } else {

            $info = D('User')->where(array(
                'user_id' => $aid
            ))->relation(true)->find();

            if (empty ($info ['user_id'])) {
                $this->error("不存在该用户ID", U('Admin/Access/index'));
            }
            if ($info ['user_login'] == 'admin') {
                $this->error("超级管理员信息不允许操作超级管理员信息或者不存在该用户", U("Admin/Access/index"));
            }

            $this->assign("info", $this->getRoleListOption($info));

            $this->action = '编辑用户';
            $this->action_name = "editUser";
            $this->assign("handle", "editUser");
            $this->display("adduser");
        }
    }

    public function delUser($aid = -1)
    {
        $user = M('User')->where(array(
            "user_id" => $aid
        ))->find();
        if ($user ['user_login'] == 'admin') {
            $this->error("管理员信息不允许操作");
        } else {
            if (D('User')->where(array(
                'user_id' => $aid
            ))->delete()
            ) {
                if (D('Role_users')->where(array(
                    'user_id' => $aid
                ))->delete()
                ) {

                    if (M('Posts')->where(array(
                        "user_id" => $aid
                    ))->find()
                    ) {
                        $post = M('Posts')->where(array(
                            "user_id" => $aid
                        ))->select();
                        foreach ($post as $v) {
                            M('Post_cat')->where(array(
                                "user_id" => $v ['user_id']
                            ))->setField('user_id', 1);
                        }
                    }

                    $this->success('用户删除成功', U('Admin/Access/index'));
                } else {
                    $this->success('用户删除不完整', U('Admin/Access/index'));
                }
            } else {
                $this->success('用户删除失败:没有找到指定用户,可能它已经被删除', U('Admin/Access/index'));
            }
        }
    }

    // 添加角色
    public function addRole()
    {
        if (IS_POST) {

            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(D('Access', 'Logic')->addRole());
        } else {
            $this->assign("info", $this->getRole());
            $this->assign("handle", "addRole");
            $this->display();
        }
    }

    public function editRole()
    {
        if (IS_POST) {

            header('Content-Type:application/json; charset=utf-8'); // p($_POST);die;
            echo json_encode(D('Access', 'Logic')->editRole());
        } else {
            $M = M("Role");
            $info = $M->where("id=" . ( int )$_GET ['id'])->find();
            if (empty ($info ['id'])) {
                $this->error("不存在该角色", U('Admin/Access/roleList'));
            }
            $this->assign("info", $this->getRole($info));
            $this->action = '编辑角色';
            $this->action_name = "editRole";
            $this->assign("handle", "editRole");
            $this->display("addrole");
        }
    }

    public function changeRole()
    {
        if (IS_POST) {
            //TODO BUG HERE

            $res = D('Access', 'Logic')->changeRole();
            if ($res['status'] == 1) $this->success("设置成功", U("Admin/Access/roleList"));
            else $this->error("设置失败，请重试");
            die();
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(D('Access', 'Logic')->changeRole());
        } else {
            $Node = D("Node");
            $info = M("Role")->where("id=" . ( int )$_GET ['id'])->find();
            if (empty ($info ['id'])) {
                $this->error("不存在该用户组", U('Admin/Access/roleList'));
            }
            $access = M("Access")->field("CONCAT(`node_id`,':',`level`,':',`pid`) as val")->where("`role_id`=" . $info ['id'])->select();
            $info ['access'] = count($access) > 0 ? json_encode($access) : json_encode(array());
            $this->assign("info", $info);
            $datas = $Node->where("level=1")->select();
            foreach ($datas as $k => $v) {
                $map ['level'] = 2;
                $map ['pid'] = $v ['id'];
                $datas [$k] ['data'] = $Node->where($map)->select();
                foreach ($datas [$k] ['data'] as $k1 => $v1) {
                    $map ['level'] = 3;
                    $map ['pid'] = $v1 ['id'];
                    $datas [$k] ['data'] [$k1] ['data'] = $Node->where($map)->select();
                }
            }
            $this->assign("nodeList", $datas);
            $this->action = "权限分配";
            $this->display();
        }
    }

    public function opNodeStatus()
    {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(D('Access', 'Logic')->opStatus("Node"));
    }

    public function opRoleStatus()
    {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(D('Access', 'Logic')->opStatus("Role"));
    }

    public function opSort()
    {
        $M = M("Node");
        $datas ['id'] = ( int )I("post.id");
        $datas ['sort'] = ( int )I("post.sort");
        header('Content-Type:application/json; charset=utf-8');
        if ($M->save($datas)) {
            echo json_encode(array(
                'status' => 1,
                'info'   => "处理成功"
            ));
        } else {
            echo json_encode(array(
                'status' => 0,
                'info'   => "处理失败"
            ));
        }
    }

    // 添加角色接受表单
    public function addRoleHandle()
    {
        if (M('Role')->add($_POST)) {
            $this->success('-_- yes！', U('relo'));
        } else {
            $this->error('-_-。sorry！');
        }
    }

    // 添加节点
    public function addNode()
    {
        /*
         * $pid=isset($_GET['pid'])?$_GET['pid']:0; $level=isset($_GET['level'])?$_GET['level']:1; $this->assign('pid',$pid); $this->assign('level',$level); switch($level){ case 1: $this->type='应用'; break; case 2: $this->type='控制器'; break; case 3: $this->type='动作方法'; break; } $this->display();
         */
        if (IS_POST) {

            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(D('Access', 'Logic')->addNode());
        } else {
            $this->assign("info", $this->getPid(array(
                'level' => 1
            )));
            $this->assign("handle", "addNode");
            $this->display();
        }
    }

    // 编辑节点
    public function editNode()
    {
        if (IS_POST) {

            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(D('Access', 'Logic')->editNode());
        } else {
            $M = M("Node");
            $info = $M->where("id=" . ( int )$_GET ['id'])->find();
            if (empty ($info ['id'])) {
                $this->error("不存在该节点", U('Admin/Access/nodeList'));
            }
            $this->assign("info", $this->getPid($info));
            $this->action = '编辑节点';
            $this->action_name = "editNode";
            $this->assign("handle", "editNode");
            $this->display('addnode');
        }
    }

    // 添加节点接受表单
    public function addNodeHandle()
    {
        // print_r($_POST);
        if (M('Node')->add($_POST)) {
            $this->success('-_- yes！', U('node'));
        } else {
            $this->error('-_-。sorry！');
        }
    }

    /*
     * // 配置权限 public function access() { $rid = $_GET ['rid']; // 读取有用字段 $field = array ( 'id', 'name', 'title', 'pid' ); $node = M ( 'node' )->order ( 'sort' )->field ( $field )->select (); // 读取用户原有权限 $access = M ( 'access' )->where ( array ( 'role_id' => $rid ) )->getField ( 'node_id', true ); $node = node_merge ( $node, $access ); $this->assign ( 'rid', $rid ); $this->assign ( 'node', $node ); $this->display (); } // 配置权限接受表单 public function setAccess() { $rid = $_POST ['rid']; $db = M ( 'access' ); // 删除原权限 $db->where ( array ( 'role_id' => $rid ) )->delete (); // 组合新权限 $data = array (); foreach ( $_POST ['access'] as $v ) { $tmp = explode ( '_', $v ); $data [] = array ( 'role_id' => $rid, 'node_id' => $tmp [0], 'level' => $tmp [1] ); } // 插入新权限 if ($db->addAll ( $data )) { $this->success ( '修改成功！', U ( 'relo' ) ); } else { $this->error ( '修改失败！' ); } }
     */
    private function getRole($info = array())
    {
        // $cat = new Category('Role', array('id', 'pid', 'name', 'remark'));
        // $list = $cat->getList(); //获取分类结构
        $Role = M('Role');
        $list = $Role->select();

        foreach ($list as $k => $v) {
            $disabled = $v ['id'] == $info ['id'] ? ' disabled="disabled"' : "";
            $selected = $v ['id'] == $info ['pid'] ? ' selected="selected"' : "";
            $info ['pidOption'] .= '<option value="' . $v ['id'] . '"' . $selected . $disabled . '>' . $v ['name'] . '</option>';
        }
        return $info;
    }

    private function getRoleListOption($info = array())
    {
        // $cat = new Category('Role', array('id', 'pid', 'name', 'remark'));
        // $list = $cat->getList(); //获取分类结构
        $Role = M('Role');
        $list = $Role->select();

        if (!empty ($info)) {
            $info ['roleOption'] = "";
            foreach ($list as $v) {

                $disabled = $v ['id'] == 1 ? ' disabled="disabled"' : "";
                $selected = $v ['id'] == $info ['name'] ['role_id'] ? ' selected="selected"' : "";
                $info ['roleOption'] .= '<option value="' . $v ['id'] . '"' . $selected . $disabled . '>' . $v ['name'] . '</option>';
            }
        } else {
            foreach ($list as $v) {
                $info ['roleOption'] .= '<option value="' . $v ['id'] . '">' . $v ['name'] . '</option>';
            }
        }

        return $info;
    }

    private function getPid($info)
    {
        $arr = array(
            "请选择",
            "项目",
            "模块",
            "操作"
        );
        for ($i = 1; $i < 4; $i++) {
            $selected = $info ['level'] == $i ? " selected='selected'" : "";
            $info ['levelOption'] .= '<option value="' . $i . '" ' . $selected . '>' . $arr [$i] . '</option>';
        }
        $level = $info ['level'] - 1;
        $cat = new Category ('Node', array(
            'id',
            'pid',
            'title',
            'fullname'
        ));
        $list = $cat->getList(); // 获取分类结构
        $option = $level == 0 ? '<option value="0" level="-1">根节点</option>' : '<option value="0" disabled="disabled">根节点</option>';
        foreach ($list as $k => $v) {
            // $disabled = $v['level'] == $level ? "" : ' disabled="disabled"';
            $selected = $v ['id'] != $info ['pid'] ? "" : ' selected="selected"';
            $option .= '<option value="' . $v ['id'] . '"' . $disabled . $selected . '  level="' . $v ['level'] . '">' . $v ['fullname'] . '</option>';
        }
        $info ['pidOption'] = $option;
        return $info;
    }


}