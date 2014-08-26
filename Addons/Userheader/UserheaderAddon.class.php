<?php

namespace Addons\Userheader;

use Common\Controller\Addon;

/**
 * 用户登录顶部导航插件
 * @author TimothyZhang
 */
class UserheaderAddon extends Addon
{

    public $info = array(
        'name' => 'Userheader',
        'title' => '用户登录顶部导航',
        'description' => '用户登录顶部导航',
        'status' => 1,
        'author' => 'TimothyZhang',
        'version' => '0.1'
    );

    public $admin_list = array(
        'model' => 'Example', //要查的表
        'fields' => '*', //要查的字段
        'map' => '', //查询条件, 如果需要可以再插件类的构造方法里动态重置这个属性
        'order' => 'id desc', //排序,
        'listKey' => array( //这里定义的是除了id序号外的表格里字段显示的表头名
            '字段名' => '表头显示名'
        ),
    );

    public function install()
    {
        $Hooks = D('Hooks');
        $userHeader= $Hooks->where(array('name' => 'userHeader'))->find();
        if (!$userHeader) {
            $data = array('name' => 'userHeader', 'description' => '用户登录顶部导航', 'type' => 1);
            $Hooks->data($data)->add();
        }

        $userHeaderLink= $Hooks->where(array('name' => 'userHeaderLink'))->find();
        if (!$userHeaderLink) {
            $data = array('name' => 'userHeaderLink', 'description' => '用户登录顶部导航链接', 'type' => 1);
            $Hooks->data($data)->add();
        }

        return true;
    }

    public function uninstall()
    {
        return true;
    }

    //实现的pageHeader钩子方法
    public function pageHeader($param)
    {
        echo '<link rel="stylesheet" href="'.get_opinion("site_url") . '/Public/share/css/admin-bar.css" />';
        echo '<link rel="stylesheet" href="'.get_opinion("site_url") . '/Public/share/css/fonts/OpenSans.css" />';

    }

    //实现的userHeader钩子方法
    public function userHeader($param)
    {

        $user_id= get_current_user_id();

        if($user_id){
            $user = D('User', 'Logic')->cache(true)->detail($user_id);
            $this->assign('user', $user);

            $this->display('admin_bar');

        }
    }


    //实现的pageFooter钩子方法
    public function pageFooter($param)
    {

        echo '<script src="'.get_opinion("site_url") . '/Public/share/js/admin-bar.js"></script>';

    }




}