<?php

namespace Addons\Guestbook;

use Common\Controller\Addon;
use Common\Util\GreenPage;

/**
 * 留言本插件
 * @author xjh1994
 */
class GuestbookAddon extends Addon
{


    public function __construct()
    {

        parent::__construct();

    }

    public $info = array(
        'name' => 'Guestbook',
        'title' => '留言本',
        'description' => '提供用户留言功能',
        'status' => 1,
        'author' => 'xjh1994',
        'version' => '0.1'
    );

    public $admin_list = array(
        'model' => 'guestbook', //要查的表
        'fields' => '*', //要查的字段
        'map' => '', //查询条件, 如果需要可以再插件类的构造方法里动态重置这个属性
        'order' => 'date desc', //排序,
        'listKey' => array( //这里定义的是除了id序号外的表格里字段显示的表头名

        ),
    );

    public function install()
    {

        $sql = "
                    CREATE TABLE IF NOT EXISTS `" . C('DB_PREFIX') . "guestbook` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `name` tinytext NOT NULL,
                      `email` varchar(100) NOT NULL,
                      `tel` varchar(100) NULL,
                      `title` varchar(255) NOT NULL,
                      `content` text NOT NULL,
                      `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                      `ip` varchar(100) NOT NULL,
                      `reply` text NULL,
                      `status` int(1) NOT NULL DEFAULT '1',
                      PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
                ";
        M()->query($sql);
        $Hooks = D('Hooks');
        $Guestbook = $Hooks->where(array('name' => 'Guestbook'))->find();
        if (!$Guestbook) {
            $data = array('name' => 'Guestbook', 'description' => 'Guestbook', 'type' => 1);
            $Hooks->data($data)->add();


        }

        $adminMenu = $Hooks->where(array('name' => 'adminSideBar'))->find();
        if (!$adminMenu) {
            $data = array('name' => 'adminSideBar', 'description' => 'adminSideBar', 'type' => 1);
            $Hooks->data($data)->add();
        }


        return true;
    }

    public function uninstall()
    {
        $sql = "DROP TABLE IF EXISTS `" . C('DB_PREFIX') . "guestbook` ";

        M()->query($sql);

        return true;
    }

    //实现的Guestbook钩子方法
    public function Guestbook($param)
    {
        // dump($param);die;
        if ($param['post_title'] == "留言板") { //前台创建的“留言板”页面
            //第一次使用，创建留言本表


            $where['status'] = 1;
            $where['reply'] = array('exp', 'is not null');

            $order = 'date DESC';

            $config = $this->getConfig();
            // dump($config);die;
            $page = $config['records'];

            $count = count(M('guestbook')->where($where)->select());

            if ($count != 0) {
                $Page = new GreenPage($count, $page); // 实例化分页类 传入总记录数
                $pager_bar = $Page->show();
                $limit = $Page->firstRow . ',' . $Page->listRows;
            }

            $message = M('guestbook')->where($where)->order($order)->limit($limit)->select();
            // dump(M('guestbook')->getLastSql());die;

            $this->assign('count', $count);
            $this->assign('pages', ceil($count / $page));
            $this->assign('pager', $pager_bar);
            $this->assign('guestbook', $message);

            $this->assign('post_id', I('info'));

            $this->display('guestbook');
        } elseif ($param = 'menu') {
            echo "
                    <li><a href='" .
                addons_url('Guestbook://Guestbook/manage') .
                "'><h4>留言板</h4></a></li>
            ";
        }
    }

    //实现的adminSideBar钩子方法
    public function adminSideBar($param)
    {
        echo '<li><a href="'. get_addon_url("Guestbook/Guestbook/manage",array(),"Admin").'">
        <i class="fa fa-angle-double-right"></i>留言板</a></li>';

    }


}