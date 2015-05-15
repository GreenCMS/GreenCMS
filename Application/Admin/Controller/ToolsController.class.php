<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: ToolsController.class.php
 * User: Timothy Zhang
 * Date: 14-3-17
 * Time: 下午9:27
 */

namespace Admin\Controller;

use Common\Event\WordpressEvent;
use Common\Logic\PostsLogic;
use Common\Logic\UserLogic;
use Common\Util\File;
use Think\Log;
use Think\Upload;

/**
 * Class ToolsController
 * @package Admin\Controller
 */
class ToolsController extends AdminBaseController
{
    /**
     *
     */
    public function index()
    {
        $this->display();

    }

    /**
     * WordPress 导入
     */
    public function wordpress()
    {
        $this->display();
    }

    /**
     * WordPress 导入处理
     */
    public function wordpressHandle()
    {

        $config = array(
            "savePath" => 'Data/',
            "maxSize" => 10000000, // 单位B
            "exts" => array('xml'),
            "subName" => array('date', 'Y/m-d'),
        );
        $upload = new Upload($config);
        $info = $upload->upload();

        if (!$info) { // 上传错误提示错误信息
            $this->error($upload->getError());
        } else { // 上传成功 获取上传文件信息

            $file_path_full = $info['file']['fullpath'];

            if (File::file_exists($file_path_full)) {
                $Wordpress = new WordpressEvent();

                $Wordpress->catImport($file_path_full);
                $Wordpress->tagImport($file_path_full);
                $Wordpress->postImport($file_path_full);


                File::delFile($file_path_full);
                $this->success('导入完成');
            }
            $this->error('导入失败');

        }


    }


    /**
     *
     */
    public function log()
    {

        $file_list = array();
        $files_list = array();

        File::getFiles(LOG_PATH, $file_list, '#\.log#i');

        foreach ($file_list as $key => $value) {
            $files_list_temp = array();
            $files_list_temp['id'] = base64_encode($value);
            $files_list_temp['name'] = $value;
            $files_list_temp['size'] = File::realSize($value);
            $files_list_temp['create_time'] = date("Y-m-d H:i:s", File::filectime($value));
            $files_list_temp['mod_time'] = date("Y-m-d H:i:s", File::filemtime($value));


            $files_list[] = $files_list_temp;

        }

        $files_list = array_sort($files_list, "mod_time");


        $this->assign('logs_list', $files_list);
        $this->display();


    }


    public function logClearHandle()
    {
        $res = File::delAll(LOG_PATH, true);
        $this->success("清除成功");

    }


    public function downFile()
    {

        if (empty($_GET['id'])) {
            $this->error("下载地址不存在");
        }

        $filename = base64_decode($_GET['id']);


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


    /*文章统计*/
    public function count()
    {

        $year = I('request.year', date('Y'));

        $month_start = I('request.month_start', date('m'));
        $month_end = I('request.month_end', date('m'));

        if ($month_start == $month_end) {
            $condition['post_date'] = array('like', I('request.year', '%') . '-' . I('request.month', '%') . '-' . I('request.day', '%') . '%');
        } else {
            if ($month_start == '%%') {
                $condition['post_date'] = array('between', "{$year}-0-0,{$year}-{$month_end}-31");
            }else            if ($month_end == '%%') {
                $condition['post_date'] = array('between', "{$year}-{$month_start}-0,{$year}-12-31");
            }else{
                $condition['post_date'] = array('between', "{$year}-{$month_start}-0,{$year}-{$month_end}-31");
            }
        }


        $UserLogic = new UserLogic();
        $PostsLogic = new PostsLogic();

        $user_list = $UserLogic->getList(false);

        foreach ($user_list as $key => $user) {
            $condition['user_id'] = $user['user_id'];
            $user_list [$key]['post_count'] = $PostsLogic->countAll('single', $condition);
            $user_list [$key]['date'] = substr($condition['post_date'][1], 0, 7);
        }

        $this->assign("user_list", $user_list);

        $this->assign("year", $year);
        $this->assign("month_start", $month_start);
        $this->assign("month_end", $month_end);
        $this->display('count');
    }


}