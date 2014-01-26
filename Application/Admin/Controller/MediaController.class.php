<?php
/**
 * Created by Green Studio.
 * File: MediaController.class.php
 * User: TianShuo
 * Date: 14-1-25
 * Time: 下午7:54
 */

namespace Admin\Controller;


class MediaController extends AdminBaseController{

    public function __construct() {
        parent::__construct();

        if(defined('SAE_TMP_PATH')){
            $this->error("当前功能不支持SAE下使用");
        }
    }

    public function file(){
        $this->display();
    }

    public function fileConnect(){
        define("IN_ADMIN",1);//重要，定义一个常量，在插件的PHP入口文件中验证，防止非法访问。
        include './Extend/GreenFinder/php/connector.php';//包含elfinder自带php接口的入口文件
    }


}