<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: UpdateEvent.class.php
 * User: Timothy Zhang
 * Date: 14-3-17
 * Time: 下午9:18
 */

namespace Common\Event;

use Common\Controller\BaseController;
use Common\Util\File;

/**
 * 升级事件
 * Class UpdateEvent
 * @package Common\Event
 */
class UpdateEvent extends BaseController
{

    /**
     *
     */
    public function check()
    {


        $software_build = get_opinion('software_build', true);
        $url = Server_API . 'api/update/' . $software_build . '/';
        $json = json_decode(file_get_contents($url), true);

        if ($json['lastest_version'] > $json['user_version']) {
            return $json;
        } else {
            return false;
        }

    }


    /**
     *
     */
    public function checkVersion()
    {
        $software_build_db = get_opinion('software_build');
        $software_build_const = GreenCMS_Build;

        if ($software_build_db == $software_build_const) {
            return true;
        } else {
            return false;
        }


    }

    public function applyPatch($filename)
    {
        $System = new SystemEvent();

        $zip = new \ZipArchive; //新建一个ZipArchive的对象
        if ($zip->open($filename) === true) {
            $zip->extractTo(WEB_ROOT); //假设解压缩到在当前路径下/文件夹内
            $zip->close(); //关闭处理的zip文件
            File::delFile($filename);
            $System->clearCacheAll();
            return $this->jsonResult(1, "安装成功");

        } else {
            return $this->jsonResult(0, "文件损坏");
        }


    }


    public function copyAndBackup($file_path1, $file_path2)
    {


    }
}