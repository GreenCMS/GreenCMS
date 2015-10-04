<?php

namespace Admin\Model;

use Common\Util\File;
use Think\Model;

/**
 * 插件模型
 */
class AddonsModel extends Model
{

    /**
     * 文件模型自动完成
     * @var array
     */
    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
    );

    /**
     * 获取插件列表
     * @param string $addon_dir
     * @param int $limit
     * @return array|bool
     */
    public function getList($addon_dir = '', $limit = 0)
    {
        if (!$addon_dir) $addon_dir = Addon_PATH;
        $dirs = array_map('basename', glob($addon_dir . '*', GLOB_ONLYDIR));

        if ($dirs === false || !File::file_exists($addon_dir)) {
            $this->error = '插件目录不可读或者不存在';
            return false;
        }


        $addons = array();
        $where['name'] = array('in', $dirs);
        $list = $this->where($where)->field(true)->limit($limit)->select();
        foreach ($list as $addon) {
            $addon['uninstall'] = 0;
            $addons[$addon['name']] = $addon;
        }
        foreach ($dirs as $value) {

            if (!isset($addons[$value])) {
                $class = get_addon_class($value);

                if (!class_exists($class)) { // 实例化插件失败忽略执行
                    \Think\Log::record('插件' . $value . '的入口文件不存在！');
                    continue;
                }
                $obj = new $class;
                $addons[$value] = $obj->info;

                if ($addons[$value]) {
                    $addons[$value]['uninstall'] = 1;
                    $addons[$value]['status'] = 99;
                }
            } else {
                //todo 已安装的
            }
        }
        int_to_string($addons, array('status' => array(-1 => '损坏', 0 => '禁用', 1 => '启用', 99 => '未安装')));
        $addons = list_sort_by($addons, 'uninstall', 'asc');

        return $addons;
    }

    /**
     * 获取插件的后台列表
     */
    public function getAdminList()
    {
        $admin = array();
        $db_addons = $this->where("status=1 AND has_adminlist=1")->field('title,name')->select();
        if ($db_addons) {
            foreach ($db_addons as $value) {
                $admin[] = array('title' => $value['title'], 'url' => "Addons/adminList?name={$value['name']}");
            }
        }
        return $admin;
    }
}
