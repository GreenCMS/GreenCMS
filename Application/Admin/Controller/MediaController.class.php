<?php
/**
 * Created by Green Studio.
 * File: MediaController.class.php
 * User: TianShuo
 * Date: 14-1-25
 * Time: 下午7:54
 */

namespace Admin\Controller;


class MediaController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->is_sae();

    }

    public function file()
    {
        $this->display();
    }

    public function fileConnect()
    {
        $opts =$this->__array('/Upload/');
        define("GREEN_CMS", 1); //重要，定义一个常量，在插件的PHP入口文件中验证，防止非法访问。
        include './Extend/GreenFinder/php/connector.php'; //包含elfinder自带php接口的入口文件
    }

    private function __array($path='/Upload/')
    {
        return array(
            // 'debug' => true,
            'roots' => array(
                array(
                    'driver' => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
                    'path' => '.'.$path, // path to files (REQUIRED)
                    'URL' => __ROOT__ . $path, // 上传文件目录的URL
                    'accessControl' => 'access' // disable and hide dot starting files (OPTIONAL)
                )
            )
        );
    }


}