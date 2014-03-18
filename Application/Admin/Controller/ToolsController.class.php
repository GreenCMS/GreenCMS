<?php
/**
 * Created by Green Studio.
 * File: ToolsController.class.php
 * User: TianShuo
 * Date: 14-3-17
 * Time: 下午9:27
 */

namespace Admin\Controller;
use Common\Util\File;

class ToolsController extends AdminBaseController
{
    public function index()
    {
        $this->display();

    }

    public function wordpress()
    {
        $this->display();
    }

    public function wordpressHandle()
    {


        if ($_FILES['file']['size'] != 0) {

            $config = array(
                "savePath"   => (WEB_CACHE_PATH),
                "maxSize"    => 100000, // 单位KB
                "allowFiles" => array(".xml")
            );

            $upload = new \Common\Util\Uploader ("file", $config);

            $info = $upload->getFileInfo();


            dump($info);

            if ($info['state'] != 'SUCCESS') {
                $this->error($info['state']);
            }

            if(File::file_exists($info['url'])){



                $Wordpress = new \Common\Event\WordpressEvent();
                $Wordpress->catImport($info['url']);
                $Wordpress->tagImport($info['url']);
                $Wordpress->postImport($info['url']);

                File::delFile($info['url']);
                $this->success('导入完成');
            }




        } else {
            $this->error('文件不能为空或者超出了服务器限制');
        }
    }

}