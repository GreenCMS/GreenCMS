<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: StatisticsAddmin.class.php
 * User: Timothy Zhang
 * Date: 14-3-9
 * Time: 下午12:28
 */

namespace Addons\Statistics;


use Common\Controller\Addon;

/**
 * Class StatisticsAddon
 * @package Addons\Statistics
 */
class StatisticsAddon extends Addon
{
    /**
     * @var array
     */
    public $info = array(
        'name'        => 'Statistics',
        'title'       => '统计插件',
        'description' => '统计插件，轻松集成到系统中。',
        'status'      => 1,
        'author'      => 'zts1993',
        'version'     => '0.1'
    );

    /**
     * @return bool
     */
    public function install()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function uninstall()
    {
        return true;
    }

    //实现的pageFooter钩子方法
    /**
     * @param $param
     */
    public function pageFooter($param)
    {
        $this->assign('addons_config', $this->getConfig());
        $this->display('analysis');
    }
}