<?php

namespace Addons\join2011;
use Common\Controller\Addon;

/**
 * 2011学院报名插件
 * @author timothy_zhang
 */

    class join2011Addon extends Addon{

        public $info = array(
            'name'=>'join2011',
            'title'=>'2011学院报名',
            'description'=>'2011学院报名',
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
            return true;
        }

        public function uninstall(){
            return true;
        }


    }