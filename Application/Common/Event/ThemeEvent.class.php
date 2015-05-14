<?php
/**
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-7-23
 * Time: 上午10:53
 */

namespace Common\Event;


use Common\Util\File;

class ThemeEvent
{

    private $theme_exist;
    private $theme_installed;
    private $theme_not_installed;

    private $theme_list_installed;

    function __construct()
    {
        if (empty($this->theme_exist)) {
            $this->getThemeNameList();
        }

        if (empty($this->theme_list_installed)) {
            $this->getThemeInstalledList();
        }

        if (empty($this->theme_installed)) {
            $this->getThemeInstalledNameList();
        }

        if (empty($this->theme_not_installed)) {
            $this->getThemeNotInstalledNameList();
        }

    }


    public function getThemeNameList()
    {

        $tpl_view_folder = File::scanDir(WEB_ROOT . 'Application/Home/View');
        $tpl_static_folder = File::scanDir(WEB_ROOT . 'Public');
        $this->theme_exist = array_intersect($tpl_view_folder, $tpl_static_folder);

        return $this->theme_exist;
    }

    public function getThemeInstalledList()
    {

        $where['theme_name'] = array('in', $this->theme_exist);

        $this->theme_list_installed = D('Theme')->where($where)->select();

        return $this->theme_list_installed;
    }


    public function getThemeInstalledNameList()
    {


        $this->theme_installed = array_keys(array_column_5($this->theme_list_installed, 'theme_name', 'theme_name'));

        return $this->theme_installed;
    }


    public function getThemeNotInstalledNameList()
    {

        $this->theme_not_installed = array_diff($this->theme_exist, $this->theme_installed);

        return $this->theme_not_installed;
    }


} 