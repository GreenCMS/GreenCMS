<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: CommonWidget.class.php
 * User: Timothy Zhang
 * Date: 14-1-25
 * Time: 上午10:41
 */

namespace Weixin\Widget;

use Org\Util\Rbac;
use Think\Controller;

/**
 * Class CommonWidget
 * @package Weixin\Widget
 */
class CommonWidget extends Controller
{
    /**
     *
     */
    public function header()
    {


        $this->display('Widget:header');

    }
    /**
     * @FBI Warning ！除非你有空闲时间，否则不要没事读这段丧心病狂的代码。。
     * @return string
     */
    public function sideMenu()
    {

        $menu = $this->show_side_menu();


        $this->assign('menu', $menu);
        $this->display('Widget:sideMenu');
    }


    private function show_side_menu()
    {
        get_opinion('admin_sub_menu', array_change_key_case(get_opinion('admin_sub_menu')));
        get_opinion('admin_big_menu', array_change_key_case(get_opinion('admin_big_menu')));

        $accessList = RBAC::getAccessList($_SESSION [get_opinion('USER_AUTH_KEY')]);
        $cache_access = array_change_key_case($accessList [strtoupper(MODULE_NAME)]);

        $cache = get_opinion('admin_big_menu');
        if ($_SESSION [get_opinion('ADMIN_AUTH_KEY')] != true) {
            foreach ($cache as $cache_key => $cache_each) {
                if (!array_key_exists($cache_key, $cache_access)) {
                    unset ($cache [$cache_key]);
                } else {
                }
            }
        }

        if ($_SESSION [get_opinion('ADMIN_AUTH_KEY')] != true) {
            foreach ($cache_access as $cache2_key => $cache2_each) {
                foreach ($cache2_each as $key => $value) {
                    $cache2_each [$key] = strtolower($cache2_key) . '/' . strtolower($key);
                }
                $cache_access [$cache2_key] = array_flip(array_change_key_case($cache2_each));
            }

            $cache2 = array_change_key_case(get_opinion('admin_sub_menu'));
            foreach ($cache2 as $cache2_key => $cache2_each) {
                $cache2 [$cache2_key] = array_change_key_case($cache2_each);
            }

            foreach ($cache_access as $cache_access_key => $cache_access_each) {
                foreach ($cache_access_each as $cache_access_each_key => $cache_access_each_each) {
                    if (!empty ($cache2 [$cache_access_key] [$cache_access_each_key])) {
                        $cache_access [$cache_access_key] [$cache_access_each_key] = $cache2 [$cache_access_key] [$cache_access_each_key];
                    } else {
                        unset ($cache_access [$cache_access_key] [$cache_access_each_key]);
                    }
                }
            }
            get_opinion('admin_sub_menu', $cache_access);
        }

        $count = count($cache);
        $i = 1;
        $menu = "";
        foreach ($cache as $url => $name) {
            if ($i == 1) {

                $css = $url == strtolower(CONTROLLER_NAME) || !$cache [strtolower(CONTROLLER_NAME)] ? "active treeview" : "treeview";
                $menu .= '

                <li class="' . $css . '">
                     <a href="#"><i class="fa fa-dashboard"></i>
                         <span>' . $name . '</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>

                    <ul class="treeview-menu">';

                $cache = get_opinion('admin_sub_menu');
                foreach ($cache as $big_url => $big_name) {
                    if ($big_url == $url)
                        foreach ($big_name as $sub_url => $sub_name) {
                            $sub_true_url = explode('/', $sub_url);
                            $css = !strcasecmp($sub_true_url [1], strtolower(ACTION_NAME)) ? "active" : "";
                            $menu .= '<li class="' . $css . '"><a href="' . U("Weixin/" . "$sub_url") . '"><i class="fa fa-angle-double-right"></i>' . $sub_name . '</a></li>';
                        }
                }

                $menu .= '</ul></li>';
            } else if ($i == $count) {

                $css = $url == strtolower(CONTROLLER_NAME) ? "treeview active" : "treeview";
                $menu .= '<li class="' . $css . '"><a href="#">
                    <i class="fa fa-th"></i>
                    <span>' . $name . '</span>
                   <i class="fa fa-angle-left pull-right"></i>

                    </a><ul class="treeview-menu">';

                $cache = get_opinion('admin_sub_menu');
                foreach ($cache as $big_url => $big_name) {
                    if ($big_url == $url)
                        foreach ($big_name as $sub_url => $sub_name) {
                            $sub_true_url = explode('/', $sub_url);
                            $css = !strcasecmp($sub_true_url [1], strtolower(ACTION_NAME)) ? "active" : "";
                            $menu .= '<li class="' . $css . '"><a href="' . U("Weixin/" . "$sub_url") . '"><i class="fa fa-angle-double-right"></i>' . $sub_name . '</a></li>';
                        }
                }
                $menu .= '</ul></li>';
            } else {
                $css = $url == strtolower(CONTROLLER_NAME) ? "treeview active" : "treeview";
                $menu .= '<li class="' . $css . '"><a href="#">
                    <i class="fa fa-th"></i>
                    <span>' . $name . '</span>
                  <i class="fa fa-angle-left pull-right"></i>

                    </a><ul class="treeview-menu">';
                $cache = get_opinion('admin_sub_menu');
                foreach ($cache as $big_url => $big_name) {
                    if ($big_url == $url)
                        foreach ($big_name as $sub_url => $sub_name) {
                            $sub_true_url = explode('/', $sub_url);
                            $css = !strcasecmp($sub_true_url [1], strtolower(ACTION_NAME)) ? "active" : "";
                            $menu .= '<li class="' . $css . '"><a href="' . U("Weixin/" . "$sub_url") . '"><i class="fa fa-angle-double-right"></i>' . $sub_name . '</a></li>';
                        }
                }
                $menu .= '</ul></li>';
            }
            $i++;
        }
        return $menu;
    }

    
    /**
     *
     */
    public function sideBar()
    {

        $menu = $this->show_all_menu();

        $this->assign('menu', $menu);
        $this->display('Widget:sideBar');
    }


    /**
     * @FBI Warning ！除非你有空闲时间，否则不要没事读这段丧心病狂的代码。。。。
     * @return string
     */
    private function show_all_menu()
    {
        get_opinion('admin_sub_menu', array_change_key_case(get_opinion('admin_sub_menu')));
        get_opinion('admin_big_menu', array_change_key_case(get_opinion('admin_big_menu')));

        $accessList = RBAC::getAccessList($_SESSION [get_opinion('USER_AUTH_KEY')]);
        $cache_access = array_change_key_case($accessList [strtoupper(MODULE_NAME)]);

        $cache = get_opinion('admin_big_menu');
        if ($_SESSION [get_opinion('ADMIN_AUTH_KEY')] != true) {
            foreach ($cache as $cache_key => $cache_each) {
                if (!array_key_exists($cache_key, $cache_access)) {
                    unset ($cache [$cache_key]);
                } else {
                }
            }
        }

        if ($_SESSION [get_opinion('ADMIN_AUTH_KEY')] != true) {
            foreach ($cache_access as $cache2_key => $cache2_each) {
                foreach ($cache2_each as $key => $value) {
                    $cache2_each [$key] = strtolower($cache2_key) . '/' . strtolower($key);
                }
                $cache_access [$cache2_key] = array_flip(array_change_key_case($cache2_each));
            }

            $cache2 = array_change_key_case(get_opinion('admin_sub_menu'));
            foreach ($cache2 as $cache2_key => $cache2_each) {
                $cache2 [$cache2_key] = array_change_key_case($cache2_each);
            }

            foreach ($cache_access as $cache_access_key => $cache_access_each) {
                foreach ($cache_access_each as $cache_access_each_key => $cache_access_each_each) {
                    if (!empty ($cache2 [$cache_access_key] [$cache_access_each_key])) {
                        $cache_access [$cache_access_key] [$cache_access_each_key] = $cache2 [$cache_access_key] [$cache_access_each_key];
                    } else {
                        unset ($cache_access [$cache_access_key] [$cache_access_each_key]);
                    }
                }
            }
            get_opinion('admin_sub_menu', $cache_access);
        }

        $count = count($cache);
        $i = 1;
        $menu = "";
        foreach ($cache as $url => $name) {
            if ($i == 1) {

                $css = $url == strtolower(CONTROLLER_NAME) || !$cache [strtolower(CONTROLLER_NAME)] ? "start active" : "start";
                $menu .= '<li class="' . $css . '"><a href="javascript:;">
                <i class="icon-home"></i>
                <span class="title">' . $name . '</span>
                <span class="arrow "></span>

                </a><ul class="sub-menu">';

                $cache = get_opinion('admin_sub_menu');
                foreach ($cache as $big_url => $big_name) {
                    if ($big_url == $url)
                        foreach ($big_name as $sub_url => $sub_name) {
                            $sub_true_url = explode('/', $sub_url);
                            $css = !strcasecmp($sub_true_url [1], strtolower(ACTION_NAME)) ? "active" : "";
                            $menu .= '<li class="' . $css . '"><a href="' . U("Weixin/" . "$sub_url") . '">' . $sub_name . '</a></li>';
                        }
                }
                $menu .= '</ul></li>';
            } else if ($i == $count) {

                $css = $url == strtolower(CONTROLLER_NAME) ? "last active" : "last";
                $menu .= '<li class="' . $css . '"><a href="javascript:;">
                    <i class="icon-cogs"></i>
                    <span class="title">' . $name . '</span>
                    <span class="arrow "></span>

                    </a><ul class="sub-menu">';

                $cache = get_opinion('admin_sub_menu');
                foreach ($cache as $big_url => $big_name) {
                    if ($big_url == $url)
                        foreach ($big_name as $sub_url => $sub_name) {
                            $sub_true_url = explode('/', $sub_url);
                            $css = !strcasecmp($sub_true_url [1], strtolower(ACTION_NAME)) ? "active" : "";
                            $menu .= '<li class="' . $css . '"><a href="' . U("Weixin/" . "$sub_url") . '">' . $sub_name . '</a></li>';
                        }
                }
                $menu .= '</ul></li>';
            } else {
                $css = $url == strtolower(CONTROLLER_NAME) ? "start active" : "";
                $menu .= '<li class="' . $css . '"><a href="javascript:;">
                    <i class="icon-cogs"></i>
                    <span class="title">' . $name . '</span>
                    <span class="arrow "></span>

                    </a><ul class="sub-menu">';
                $cache = get_opinion('admin_sub_menu');
                foreach ($cache as $big_url => $big_name) {
                    if ($big_url == $url)
                        foreach ($big_name as $sub_url => $sub_name) {
                            $sub_true_url = explode('/', $sub_url);
                            $css = !strcasecmp($sub_true_url [1], strtolower(ACTION_NAME)) ? "active" : "";
                            $menu .= '<li class="' . $css . '"><a href="' . U("Weixin/" . "$sub_url") . '">' . $sub_name . '</a></li>';
                        }
                }
                $menu .= '</ul></li>';
            }
            $i++;
        }
        return $menu;
    }


    /**
     *
     */
    public function footer()
    {


        $footer_content = get_opinion('footer_content');

        $this->assign('footer_content', $footer_content);
        $this->display('Widget:footer');
    }

}