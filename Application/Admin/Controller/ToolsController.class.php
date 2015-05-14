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
    public function count(){
        $this->display();
    }

    public function countHandle() {
        $year=I('year');
        $month=I('month');
        
        if ($month!=0) {
            $m['post_date']=array('between',"{$_POST['year']}-{$_POST['month']}-0,{$_POST['year']}-{$_POST['month']}-31");
        }else{
            $m['post_date']=array('between',"{$_POST['year']}-0-0,{$_POST['year']}-12-31");
        }
        
        $field=array('post_id','user_id','post_date','post_title');
        $data=M('posts')->field($field)->where($m)->select();
        $r=arr_merge($data);
        foreach ($r as $key => $value) {
            $g=M('user')->where(array('user_id'=>$key))->find();
            $r[$key]['user_login']=$g['user_login'];
            $r[$key]['user_name']=$g['user_nicename'];
            $r[$key]['date']=$year."-".$month;
        }
        $this->r=$r;
        $this->display('count');
    }



}