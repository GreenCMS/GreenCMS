<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: AccessController.class.php
 * User: Timothy Zhang
 * Date: 14-1-26
 * Time: 下午5:26
 */

namespace Admin\Controller;


use Admin\Logic\AccessLogic;
use Common\Event\AccessEvent;
use Common\Logic\LogLogic;
use Common\Logic\UserLogic;
use Common\Util\Category;
use Common\Util\GreenPage;

/**
 * Class AccessController
 * @package Admin\Controller
 */
class AccessController extends AdminBaseController
{

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    // 用户列表
    /**
     *
     */
    public function index()
    {
        $page = I('get.page', 20);

        $UserLogic = new UserLogic();

        $where = array("user_level" => array('neq', 5));
        $count = $UserLogic->where($where)->count();

        if ($count != 0) {
            $Page = new GreenPage($count, $page); // 实例化分页类 传入总记录数
            $pager_bar = $Page->show();
            $limit = $Page->firstRow . ',' . $Page->listRows;
            $list = D('Access', 'Logic')->adminList($limit);
        }

        $this->assign('listname', '管理组用户');

        $this->assign('pager', $pager_bar);
        $this->assign('list', $list);
        $this->display('userlist');
    }

    // 游客列表
    /**
     *
     */
    public function guest()
    {
        $page = I('get.page', get_opinion('PAGER'));

        $UserLogic = new UserLogic();

        $where = array("user_level" => array('eq', 5));
        $count = $UserLogic->where($where)->count();

        if ($count != 0) {
            $Page = new GreenPage($count, $page); // 实例化分页类 传入总记录数
            $pager_bar = $Page->show();
            $limit = $Page->firstRow . ',' . $Page->listRows;
            $list = D('Access', 'Logic')->guestList($limit);
        }

        $this->assign('pager', $pager_bar);
        $this->assign('listname', '游客用户');
        $this->assign('list', $list);

        $this->display('userlist');
    }

    // 用户锁定处理
    /**
     * @param $id
     */
    public function indexlocked($id)
    {
        // 锁定用户
        if ($id == 1) { // 判断用户是否为超级管理员，如果是不能做任何操作（我的超级管理员ID为1）
            $this->error('对不起，您不能对此用户做任何操作！');
        } else {
            $i = M('User')->where(array('id' => $id))->setField('user_status', '0');
            $this->success('用户已关闭！');
        }
    }

    // 用户解锁处理
    /**
     * @param $id
     */
    public function indexlock($id)
    {
        // 解锁用户
        if ($id == 1) { // 判断用户是否为超级管理员，如果是不能做任何操作（我的超级管理员ID为1）
            $this->error('对不起，您不能对此用户做任何操作！');
        } else {
            $i = M('User')->where(array('id' => $id))->setField('lock', '1');
            $this->success('用户已开启！');
        }
    }


    // 角色列表
    /**
     *
     */
    public function rolelist()
    {
        $this->assign('rolelist', D('Access', 'Logic')->roleList());
        $this->display();
    }


    // 节点列表
    /**
     *
     */
    public function nodelist()
    {
//        $field = array(
//            'id',
//            'name',
//            'title',
//            'pid'
//        );
        // $node = M('node')->field($field)->order('sort')->select();
        $node = D('Access', 'Logic')->nodeList();
        // $node=node_merge($node);
        // print_r($node);die;
        $this->assign('node', $node);
        $this->display();
    }


    public function rebuildAccess()
    {
        D('Node')->where('1')->delete(); //清空
        D('Access')->where('1')->delete(); //清空

        $Access = new AccessEvent();
        $Access->initAdmin();
        $Access->initWeixin();


        $this->success("重建完成，请重新分配权限");

    }

    // 添加用户
    /**
     *
     */
    public function addUser()
    {
        $role = D('role')->select();
        $this->assign('info', $this->getRoleListOption());
        $this->assign("action_name",'addUser' );
        $this->display("adduser");
    }

    // 添加用户表单接受

    /**
     * @param array $info
     * @return array
     */
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
                $selected = $v ['id'] == $info ['user_role'] ['role_id'] ? ' selected="selected"' : "";
                $info ['roleOption'] .= '<option value="' . $v ['id'] . '"' . $selected . $disabled . '>' . $v ['name'] . '</option>';
            }
        } else {
            foreach ($list as $v) {
                $info ['roleOption'] .= '<option value="' . $v ['id'] . '">' . $v ['name'] . '</option>';
            }
        }

        return $info;
    }

    /**
     *
     */
    public function addUserHandle()
    {
        $UserLogic = new UserLogic();

        $user_login = htmlspecialchars(trim($_POST ['user_login']));
        $userDetail = $UserLogic->detailByUserlogin($user_login);
        if ($userDetail != '') {
            $this->error('用户名已存在！');
        } else {
            // 组合用户信息并添加

            $user = array(
                'user_login' => I('post.user_login'),
                'user_nicename' => I('post.user_nicename'),
                'user_pass' => encrypt(I('post.password')),
                'user_email' => I('post.user_email'),
                'user_url' => I('post.user_url'),
                'user_intro' => I('post.user_intro'),
                'user_status' => I('post.user_status'),
                'user_level' => I('post.role_id'),

            );
            // 添加用户与角色关系

            $res = $UserLogic->addUser($user);
            $this->array2Response($res);

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
//            header('Content-Type:application/json; charset=utf-8');
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

    // 添加角色

    /**
     * @param $aid
     */
    public function delUser($aid = -1)
    {
        $user = M('User')->where(array("user_id" => $aid))->find();
        if ($user ['user_login'] == 'admin') {
            $this->error("管理员信息不允许操作");
        } else {
            if (D('User')->where(array('user_id' => $aid))->delete()) {
                if (D('Role_users')->where(array('user_id' => $aid))->delete()) {

                    if (M('Posts')->where(array("user_id" => $aid))->find()) {
                        $post = M('Posts')->where(array("user_id" => $aid))->select();
                        foreach ($post as $v) {
                            M('Post_cat')->where(array("user_id" => $v ['user_id']))->setField('user_id', 1);
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

    /**
     *
     */
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

    /**
     * @param array $info
     * @return array
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

    /**
     *
     */
    public function editRole()
    {
        if (IS_POST) {

            header('Content-Type:application/json; charset=utf-8'); // p($_POST);die;
            echo json_encode(D('Access', 'Logic')->editRole());
        } else {
            $Role = M("Role");
            $info = $Role->where("id=" . ( int )$_GET ['id'])->find();
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

    /**
     *
     */
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

    /**
     * 投稿员指定分类
     */
    public function setrolecat($id)
    {
        if (IS_POST) {

            $data["cataccess"] = json_encode(I('post.cats'));

            $res = D('Role')->where(array('id' => $id))->data($data)->save();
            if ($res) {
                $this->success("保存成功");
            } else {
                $this->error("保存失败");
            }

        } else {
            $role = D('Role')->where(array('id' => $id))->find();

            $this->user_cats = json_decode($role['cataccess']);

            $this->action = '指定分类';
            $this->action_name = "setrolecat";
            $this->assign("handle", U("Admin/Access/setrolecat", array('id' => $id)));
            $this->cats = D('Cats', 'Logic')->category();
            $this->display();
        }

    }
    /**
     * 投稿员指定分类
     */
    public function setusercat($id)
    {
        if (IS_POST) {

            $data["cataccess"] = json_encode(I('post.cats'));

            $res = D('User')->where(array('user_id' => $id))->data($data)->save();
            if ($res) {
                $this->success("保存成功");
            } else {
                $this->error("保存失败");
            }

        } else {
            $role = D('User')->where(array('user_id' => $id))->find();

            $this->user_cats = json_decode($role['cataccess']);

            $this->action = '指定分类';
            $this->action_name = "setusercat";
            $this->assign("handle", U("Admin/Access/setusercat", array('id' => $id)));
            $this->cats = D('Cats', 'Logic')->category();
            $this->display();
        }

    }

    /**
     *
     */
    public function opNodeStatus()
    {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(D('Access', 'Logic')->opStatus("Node"));
    }

    // 添加角色接受表单

    /**
     *
     */
    public function opRoleStatus()
    {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(D('Access', 'Logic')->opStatus("Role"));
    }

    // 添加节点

    /**
     *
     */
    public function opSort()
    {
        $M = M("Node");
        $datas ['id'] = ( int )I("post.id");
        $datas ['sort'] = ( int )I("post.sort");
        header('Content-Type:application/json; charset=utf-8');
        if ($M->save($datas)) {
            $this->jsonReturn(1, "处理成功");
        } else {
            $this->jsonReturn(0, "处理失败");
        }
    }

    // 编辑节点

    /**
     *
     */
    public function addRoleHandle()
    {
        if (M('Role')->add($_POST)) {
            $this->success('-_- yes！', U('relo'));
        } else {
            $this->error('-_-。sorry！');
        }
    }

    // 添加节点接受表单

    /**
     *
     */
    public function addNode()
    {
        /*
         * $pid=isset($_GET['pid'])?$_GET['pid']:0; $level=isset($_GET['level'])?$_GET['level']:1; $this->assign('pid',$pid); $this->assign('level',$level); switch($level){ case 1: $this->type='应用'; break; case 2: $this->type='控制器'; break; case 3: $this->type='动作方法'; break; } $this->display();
         */
        if (IS_POST) {

            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(D('Access', 'Logic')->addNode());
        } else {
            $this->assign("info", $this->getPid(array('level' => 1)));
            $this->assign("handle", "addNode");
            $this->display();
        }
    }

    /*
     * // 配置权限 public function access() { $rid = $_GET ['rid']; // 读取有用字段 $field = array ( 'id', 'name', 'title', 'pid' ); $node = M ( 'node' )->order ( 'sort' )->field ( $field )->select (); // 读取用户原有权限 $access = M ( 'access' )->where ( array ( 'role_id' => $rid ) )->getField ( 'node_id', true ); $node = node_merge ( $node, $access ); $this->assign ( 'rid', $rid ); $this->assign ( 'node', $node ); $this->display (); } // 配置权限接受表单 public function setAccess() { $rid = $_POST ['rid']; $db = M ( 'access' ); // 删除原权限 $db->where ( array ( 'role_id' => $rid ) )->delete (); // 组合新权限 $data = array (); foreach ( $_POST ['access'] as $v ) { $tmp = explode ( '_', $v ); $data [] = array ( 'role_id' => $rid, 'node_id' => $tmp [0], 'level' => $tmp [1] ); } // 插入新权限 if ($db->addAll ( $data )) { $this->success ( '修改成功！', U ( 'relo' ) ); } else { $this->error ( '修改失败！' ); } }
     */

    /**
     * @param $info
     * @return mixed
     */
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

    /**
     *
     */
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
            $this->assign('action', '编辑节点');
            $this->assign('action_name', 'editNode');
            $this->assign("handle", "editNode");
            $this->display('addnode');
        }
    }

    /**
     *
     */
    public function addNodeHandle()
    {
        // print_r($_POST);
        if (M('Node')->add($_POST)) {
            $this->success('-_- yes！', U('node'));
        } else {
            $this->error('-_-。sorry！');
        }
    }

    public function loginlogclearHandle()
    {

        if (D('login_log')->where(1)->delete()) {
            $this->success("删除成功");
        } else {
            $this->error("删除失败");

        }

    }

    public function loginlog()
    {
        $page = I('get.page', 20);

        $Login_log = D('login_log');
        $count = $Login_log->count(); // 查询满足要求的总记录数

        if ($count != 0) {
            $Page = new GreenPage($count, $page); // 实例化分页类 传入总记录数
            $pager_bar = $Page->show();
            $limit = $Page->firstRow . ',' . $Page->listRows;
            $log = $Login_log->limit($limit)->select();

        }


        $this->assign('pager_bar', $pager_bar);

        $this->assign('log', $log);

        $this->display();


    }

    public function profile($uid)
    {

        if ($uid != get_current_user_id()) {
            $this->error("只能浏览自己的档案");
        }

        $this->profileAll($uid);

    }

    public function profileAll($uid)
    {

        $user = D('User', 'Logic')->cache(true)->detail($uid);
        unset($user['user_pass']);
        unset($user['user_session']);
        unset($user['user_activation_key']);
        $this->assign('user', $user);
        $this->assign('action', '用户档案');

        $this->display("profile");


    }

    public function log()
    {

        $page = I('get.page', 20);
        $where = I('get.');


        $LogLogic = new LogLogic();

        $count = $LogLogic->countAll($where); // 查询满足要求的总记录数


        if ($count != 0) {

            $Page = new GreenPage($count, $page); // 实例化分页类 传入总记录数
            $pager_bar = $Page->show();
            $limit = $Page->firstRow . ',' . $Page->listRows;
            $log_list = $LogLogic->getList($limit, $where);

        }


        $this->assign('pager_bar', $pager_bar);
        $this->assign('log_list', $log_list);

        $this->display();

    }

    public function logclearHandle()
    {
        if (D('log')->where(1)->delete()) {
            $this->success("删除成功");
        } else {
            $this->error("删除失败");

        }

    }
}