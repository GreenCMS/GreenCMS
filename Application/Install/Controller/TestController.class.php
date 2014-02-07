<?php
/**
 * Created by Green Studio.
 * File: TestController.class.php
 * User: TianShuo
 * Date: 14-2-5
 * Time: 上午11:56
 */

namespace Install\Controller;

use Think\Controller;

class TestController extends Controller
{

    public function __construct()
    {
        parent::__construct();



    }


    /**
     * test only 生产模式需要删除这个代码
     */
    public function uninstall($key)
    {
        if($key!='zts')die("No access");


        $file2 = WEB_ROOT . 'Data/Install/install.lock';
        unlink($file2);
        $this->redirect('Install/Index/index');

    }


    public function init($key)
    {

         if($key!='zts')die("No access");


        $test = true;

        $NODE = 'Node';
        $data = array();
        $data['name'] = 'Admin';
        $data['title'] = '后台管理';
        $data['status'] = 1;
        $data['remark'] = '后台管理';
        $data['sort'] = 0;
        $data['pid'] = 0;
        $data['level'] = 1;
        dump($data);

        if ($test) D($NODE)->where('1')->delete();


        if ($test) D($NODE)->data($data)->add();


        $level1_map['name'] = 'Admin';
        $level1_map['level'] = 1;

        $level1_temp = D($NODE)->field('id')->where($level1_map)->find();
        dump((int)$level1_temp['id']);


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


//        dump($AdminBaseController);
//        dump($IndexController);
//        dump($AccessController);
//        dump($CustomController);
//        dump($DataController);
//        dump($SystemController);
//        dump($PostsController);
//        dump($MediaController);
//        dump($UeditorController);


        $Controllers = array('IndexController', 'AccessController', 'CustomController', 'DataController'
        , 'SystemController', 'MediaController', 'UeditorController', 'PostsController');

        foreach ($Controllers as $value) {
            $data = array();
            $data['name'] = substr($value,0,-10);
            $data['title'] = $value;
            $data['status'] = 1;
            $data['remark'] = $value;
            $data['sort'] = 0;
            $data['pid'] = (int)$level1_temp['id'];
            $data['level'] = 2;
            echo 'Controller:'.$value;dump($data);
            if ($test) D($NODE)->data($data)->add();
        }
        $map['id'] = array('neq', 1);
        $Nodes = D('Node')->field('name')->where($map)->select();
        dump($Nodes);


        foreach ($Nodes as $key => $value) {
            $value['name']=$value['name'].'Controller';
            $temp = $$value['name'];

            $map2['name'] = substr($value['name'],0,-10);
            $map2['level'] = 2;

            $temp2 = D($NODE)->field('id')->where($map2)->find();
            dump((int)$temp2['id']);
            dump($temp);

            foreach ($temp as $key => $value) {
                $data = array();
                $data['name'] = $value;
                $data['title'] = $data['name'];
                $data['status'] = 1;
                $data['remark'] = $data['name'];
                $data['sort'] = 0;
                $data['pid'] = (int)$temp2['id'];
                $data['level'] = 3;
                dump($data);

                if ($test) D($NODE)->data($data)->add();

            }


        }


    }

}