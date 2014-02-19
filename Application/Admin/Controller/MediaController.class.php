<?php
/**
 * Created by Green Studio.
 * File: MediaController.class.php
 * User: TianShuo
 * Date: 14-1-25
 * Time: 下午7:54
 */

namespace Admin\Controller;

use Common\Util\File;
use Think\Think;

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

        define('GreenCMS', 'GreenCMS');
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
                'path'          => WEB_ROOT . $path, // path to files (REQUIRED)//'./'
                'URL'           => __ROOT__ . '/' . $path, // 上传文件目录的URL
                'accessControl' => 'access' // disable and hide dot starting files (OPTIONAL)
            );

            array_push($opts['roots'], $single_root);

        }

        return $opts;
    }


    public function backupFile()
    {
        $root = File::scanDir(WEB_ROOT,true);
        $root_info = array();

        foreach ($root as $value) {
            $root_info_temp = array();
            $root_info_temp['name'] = $value;
            $root_info_temp['path'] =  WEB_ROOT . $value;
            $root_info_temp['size'] = File::realSize(WEB_ROOT . $value);

            array_push($root_info,$root_info_temp);
        }
      // dump($root_info);
        $this->assign('root_info', $root_info);
        $this->display();
    }

    public function backupFileHandle()
    {

         $System = new \Common\Event\SystemEvent();
        $res = $System->backupFile(I('post.file'));

        if($res['status']==1)
            $this->success('成功备份到文件'.$res['info']);
    }

    public function restoreFile()
    {

        $this->display();
    }


}