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
        $roots = array('Upload/', 'Public/', 'Application/');
        $opts = $this->__array($roots);

        define('GreenCMS','GreenCMS');
        //echo WEB_ROOT . 'Extend/GreenFinder/php/connector.php';
        include WEB_ROOT . 'Extend/GreenFinder/php/connector.php'; //包含elfinder自带php接口的入口文件
    }

    private function __array($paths = array())
    {
        $opts = array(
            // 'debug' => true,
            'roots' => array(),
        );

        foreach ($paths as $path) {
            $single_root = array(
                'driver'        => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
                'path'          =>  WEB_ROOT. $path, // path to files (REQUIRED)//'./'
                'URL'           => __ROOT__ .'/'. $path, // 上传文件目录的URL
                'accessControl' => 'access' // disable and hide dot starting files (OPTIONAL)
            );

            array_push($opts['roots'], $single_root);

        }

        return $opts;
    }


}