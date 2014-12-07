<?php

namespace Addons\GreenWeixin;
use Common\Controller\Addon;

/**
 * 微信模块插件
 * @author timothy_zhang
 */

    class GreenWeixinAddon extends Addon{

        public $info = array(
            'name'=>'GreenWeixin',
            'title'=>'微信模块',
            'description'=>'微信模块入口',
            'status'=>1,
            'author'=>'timothy_zhang',
            'version'=>'0.1'
        );

        public $admin_list = array(
            'model'=>'Example',     //要查的表
                                            'fields'=>'*',          //要查的字段
                                            'map'=>'',              //查询条件, 如果需要可以再插件类的构造方法里动态重置这个属性
                                            'order'=>'id desc',     //排序,
                                            'listKey'=>array(       //这里定义的是除了id序号外的表格里字段显示的表头名
                                            '字段名'=>'表头显示名'
                                            ),
        );

        public function install(){

            $Hooks = D('Hooks');


            $adminMenu = $Hooks->where(array('name' => 'adminSideBar'))->find();
            if (!$adminMenu) {
                $data = array('name' => 'adminSideBar', 'description' => 'adminSideBar', 'type' => 1);
                $Hooks->data($data)->add();
            }


            return true;
        }

        public function uninstall(){
            return true;
        }



        //实现的adminSideBar钩子方法
        public function adminSideBar($param)
        {
            if(check_access("Weixin/Index/index")){

                echo '<li><a href="'.U('Weixin/Index/index').'">
        <i class="fa fa-angle-double-right"></i>微信模块</a></li>';

            }else{

            }

        }



    }