<?php
/**
 * Created by Green Studio.
 * File: ToolsController.class.php
 * User: TianShuo
 * Date: 14-3-17
 * Time: 下午9:27
 */

namespace Admin\Controller;

use Common\Event\WordpressEvent;
use Common\Util\File;
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
     *
     */
    public function wordpress()
    {
        $this->display();
    }

    /**
     *
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

        }


    }

}