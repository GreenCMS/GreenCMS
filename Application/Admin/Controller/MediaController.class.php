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
    }

    public function file()
    {
        $this->is_sae();
        $this->display();
    }

    public function fileConnect()
    {
        $this->is_sae();

        $opts = array(
            // 'debug' => true,
            'roots' => array(
                array(
                    'driver' => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
                    'path' => './Upload/', // path to files (REQUIRED)
                    'URL' => __ROOT__ . '/Upload/', // 上传文件目录的URL
                    'accessControl' => 'access' // disable and hide dot starting files (OPTIONAL)
                )
            )
        );
        define("GREEN_CMS", 1); //重要，定义一个常量，在插件的PHP入口文件中验证，防止非法访问。
        include './Extend/GreenFinder/php/connector.php'; //包含elfinder自带php接口的入口文件
    }


}