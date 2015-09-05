<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: AccessEvent.class.php
 * User: Timothy Zhang
 * Date: 14-3-2
 * Time: 上午9:45
 */

namespace Common\Event;


/**
 * Class AccessEvent
 * @package Install\Event
 */
class AccessEvent
{

    public function checkAccess()
    {

        $access_count = D("Access")->count();

        if ($access_count) return true;
        return false;

    }


    public function checkNode()
    {

        $node_count = D("Node")->count();

        if ($node_count) return true;
        return false;

    }

    //测试型节点批量添加
    /**
     *
     */
    public function initAdmin()
    {

        $NODE = 'Node';
        $data = array();
        $data['name'] = 'Admin';
        $data['title'] = '后台管理';
        $data['status'] = 1;
        $data['remark'] = '后台管理';
        $data['sort'] = 0;
        $data['pid'] = 0;
        $data['level'] = 1;

        D($NODE)->where('1')->delete(); //清空
        D($NODE)->data($data)->add();


        $level1_map['name'] = 'Admin';
        $level1_map['level'] = 1;

        $level1_temp = D($NODE)->field('id')->where($level1_map)->find();


        $AdminBaseController = get_class_methods(new \Admin\Controller\AdminBaseController());
        //消除继承得到的方法
        $AccessController = array_diff(get_class_methods(new \Admin\Controller\AccessController()), $AdminBaseController);
        $CustomController = array_diff(get_class_methods(new \Admin\Controller\CustomController()), $AdminBaseController);
        $IndexController = array_diff(get_class_methods(new \Admin\Controller\IndexController()), $AdminBaseController);
        $DataController = array_diff(get_class_methods(new \Admin\Controller\ DataController()), $AdminBaseController);
        $SystemController = array_diff(get_class_methods(new \Admin\Controller\SystemController()), $AdminBaseController);
        $PostsController = array_diff(get_class_methods(new \Admin\Controller\PostsController()), $AdminBaseController);
        $MediaController = array_diff(get_class_methods(new \Admin\Controller\MediaController()), $AdminBaseController);
        $UeditorController = array_diff(get_class_methods(new \Admin\Controller\UeditorController()), $AdminBaseController);
        $ToolsController = array_diff(get_class_methods(new \Admin\Controller\ToolsController()), $AdminBaseController);
        $AddonsController = array_diff(get_class_methods(new \Admin\Controller\AddonsController()), $AdminBaseController);
        $MemberController = array_diff(get_class_methods(new \Admin\Controller\MemberController()), $AdminBaseController);


        $Controllers = array('IndexController', 'AccessController', 'CustomController', 'DataController'
        , 'SystemController', 'MediaController', 'UeditorController', 'PostsController', 'ToolsController'
        , 'AddonsController', 'MemberController');

        foreach ($Controllers as $value) {
            $data = array();
            $data['name'] = substr($value, 0, -10);
            $data['title'] = $value;
            $data['status'] = 1;
            $data['remark'] = $value;
            $data['sort'] = 0;
            $data['pid'] = (int)$level1_temp['id'];
            $data['level'] = 2;
            D($NODE)->data($data)->add();
        }
        $map['id'] = array('neq', 1);
        $Nodes = D('Node')->field('name')->where($map)->select();


        foreach ($Nodes as $key => $value) {
            $value['name'] = $value['name'] . 'Controller';
            $temp = $$value['name'];

            $map2['name'] = substr($value['name'], 0, -10);
            $map2['level'] = 2;

            $temp2 = D($NODE)->field('id')->where($map2)->find();

            foreach ($temp as $key => $value) {
                $data = array();
                $data['name'] = $value;
                $data['title'] = $data['name'];
                $data['status'] = 1;
                $data['remark'] = $data['name'];
                $data['sort'] = 0;
                $data['pid'] = (int)$temp2['id'];
                $data['level'] = 3;

                D($NODE)->data($data)->add();

            }


        }


    }

    /**
     *
     */
    public function initWeixin()
    {


        $NODE = 'Node';
        $data = array();
        $data['name'] = 'Weixin';
        $data['title'] = '微信管理';
        $data['status'] = 1;
        $data['remark'] = '微信管理';
        $data['sort'] = 100;
        $data['pid'] = 0;
        $data['level'] = 1;

        D($NODE)->data($data)->add();

        $level1_map['name'] = 'Weixin';
        $level1_map['level'] = 1;

        $level1_temp = D($NODE)->field('id')->where($level1_map)->find();


        $WeixinBaseController = get_class_methods(new \Weixin\Controller\WeixinBaseController());
        //消除继承得到的方法
        $HomeController = array_diff(get_class_methods(new \Weixin\Controller\HomeController()), $WeixinBaseController);
        $MenuController = array_diff(get_class_methods(new \Weixin\Controller\MenuController()), $WeixinBaseController);
        $MessageController = array_diff(get_class_methods(new \Weixin\Controller\MessageController()), $WeixinBaseController);
        $ReplyController = array_diff(get_class_methods(new \Weixin\Controller\ReplyController()), $WeixinBaseController);
        $RuleController = array_diff(get_class_methods(new \Weixin\Controller\RuleController()), $WeixinBaseController);
        $SystemController = array_diff(get_class_methods(new \Weixin\Controller\SystemController()), $WeixinBaseController);
        $UserController = array_diff(get_class_methods(new \Weixin\Controller\UserController()), $WeixinBaseController);


        $Controllers = array('HomeController', 'MenuController', 'MessageController', 'ReplyController'
        , 'SystemController', 'RuleController', 'UserController');

        foreach ($Controllers as $value) {
            $data = array();
            $data['name'] = substr($value, 0, -10);
            $data['title'] = $value;
            $data['status'] = 1;
            $data['remark'] = $value;
            $data['sort'] = 0;
            $data['pid'] = (int)$level1_temp['id'];
            $data['level'] = 2;
            D($NODE)->data($data)->add();
        }
        $map['id'] = array('neq', 1);
        $Nodes = D('Node')->field('name')->where($map)->select();


        foreach ($Nodes as $key => $value) {
            $value['name'] = $value['name'] . 'Controller';
            $temp = $$value['name'];

            $map2['name'] = substr($value['name'], 0, -10);
            $map2['level'] = 2;

            $temp2 = D($NODE)->field('id')->where($map2)->find();

            foreach ($temp as $key => $value) {
                $data = array();
                $data['name'] = $value;
                $data['title'] = $data['name'];
                $data['status'] = 1;
                $data['remark'] = $data['name'];
                $data['sort'] = 0;
                $data['pid'] = (int)$temp2['id'];
                $data['level'] = 3;

                D($NODE)->data($data)->add();

            }


        }


    }
}