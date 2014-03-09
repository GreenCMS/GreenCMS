<?php
/**
 * Created by Green Studio.
 * File: StatisticsAddmin.class.php
 * User: TianShuo
 * Date: 14-3-9
 * Time: 下午12:28
 */

namespace Addons\Statistics;


use Common\Controller\Addon;

class StatisticsAddon extends Addon
{
    public $info = array(
        'name'        => 'Statistics',
        'title'       => '统计插件',
        'description' => '统计插件，轻松集成到系统中。',
        'status'      => 1,
        'author'      => 'zts1993',
        'version'     => '0.1'
    );

    public function install()
    {
        return true;
    }

    public function uninstall()
    {
        return true;
    }

    //实现的pageFooter钩子方法
    public function documentDetailAfter($param)
    {
      //  die('StatisticsAddon');
  //      $this->assign('addons_config', $this->getConfig());
   //     $this->display('comment');
    }
}