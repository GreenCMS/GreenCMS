<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: MediaController.class.php
 * User: Timothy Zhang
 * Date: 14-1-25
 * Time: 下午7:54
 */

namespace Admin\Controller;

use Common\Event\SystemEvent;
use Common\Util\File;
use Think\Storage;
use Think\Think;

/**
 * Class MediaController
 * @package Admin\Controller
 */
class MediaController extends AdminBaseController
{

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        //gc_disable();
    }


    /**
     *
     */
    public function file()
    {
        $this->display();
    }

    /**
     *
     */
    public function fileConnect()
    {
        $roots = array('Upload/', 'Application/', 'Public/', ''); //
        $opts = $this->__array($roots);

        define('GreenCMS', 'GreenCMS');

        include WEB_ROOT . 'Extend/Elfinder/php/connector.php'; //包含elfinder自带php接口的入口文件
    }

    /**
     * @param array $paths
     * @return array
     */
    private function __array($paths = array())
    {
        $opts = array(
            // 'debug' => true,
            'roots' => array(),
        );

        foreach ($paths as $path) {
            $single_root = array(
                'driver' => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
                'path' => './' . $path, // path to files (REQUIRED)// WEB_ROOT .
                'URL' => __ROOT__ . '/' . $path, // 上传文件目录的URL
                'accessControl' => 'access' // disable and hide dot starting files (OPTIONAL)
            );

            array_push($opts['roots'], $single_root);

        }

        return $opts;
    }


    /**
     *
     */
    public function backupFile()
    {
        $root = File::scanDir(WEB_ROOT, true);
        $root_info = array();

        $filter_array = array("Data", "Install", "README.md", "UpdateLOG", "composer.json");

        foreach ($root as $value) {

            if (in_array($value, $filter_array)) continue;


            $root_info_temp = array();
            $root_info_temp['name'] = $value;
            $root_info_temp['path'] = WEB_ROOT . $value;
            $root_info_temp['size'] = File::realSize(WEB_ROOT . $value);

            array_push($root_info, $root_info_temp);
        }
        // dump($root_info);
        $this->assign('root_info', $root_info);
        $this->display();
    }

    /**
     *
     */
    public function backupFileHandle()
    {

        $System = new SystemEvent();
        $res = $System->backupFile(I('post.file'));

        if ($res['status'] == 1)
            $this->success('成功备份到文件' . $res['info']);
    }

    /**
     *
     */
    public function restoreFile()
    {
        $handle = opendir(System_Backup_PATH);

        $file_list = array();

        File::getFiles(System_Backup_PATH, $file_list, '#\.zip$#i');

        foreach ($file_list as $key => $value) {
            $files_list_temp = array();
            $files_list_temp['id'] = base64_encode($value);
            $files_list_temp['name'] = $value;
            $files_list_temp['size'] = File::realSize($value);
            $files_list_temp['create_time'] = date("Y-m-d H:i:s", File::filectime($value));
            $files_list_temp['mod_time'] = date("Y-m-d H:i:s", File::filemtime($value));


            $files_list[] = $files_list_temp;

        }


        $this->assign('backup', $files_list);
        $this->display();
    }


    public function delFileHandle($id)
    {
        $file_name = base64_decode($id);

        if (File::delFile($file_name)) {

            $this->success('删除成功');
        }

    }

    public function restoreFileHandle($id = '')
    {


        $file_name = base64_decode($id);
        $System = new SystemEvent();


        $zip = new \ZipArchive; //新建一个ZipArchive的对象
        if ($zip->open($file_name) === true) {
            $zip->extractTo(WEB_ROOT);
            $zip->close(); //关闭处理的zip文件

            //File::delFile($file_name);

            $System->clearCacheAll();
        } else {
            $this->error('文件损坏');
        }

        $SystemEvent = new SystemEvent;
        $SystemEvent->clearCacheAll();

        $this->success('还原成功');
    }


    public function downFile()
    {

        if (empty($_GET['id'])) {
            $this->error("下载地址不存在");
        }

        $filename = base64_decode($_GET['id']);

        if (strpos(I("get.file"), "..") !== false) {
            $this->error("非法请求");
        }

        $filePath = $filename;

        if (!file_exists($filePath)) {
            $this->error("该文件不存在，可能是被删除");
        }
        $filename = basename($filePath);
        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header("Content-Length: " . filesize($filePath));
        readfile($filePath);
    }


}