<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: CommonWidget.class.php
 * User: Timothy Zhang
 * Date: 14-1-24
 * Time: 下午2:52
 */

namespace Home\Widget;

use Home\Logic\MenuLogic;
use Think\Controller;

/**
 * 通用小固件
 * Class CommonWidget
 * @package Home\Widget
 */
class CommonWidget extends Controller
{
    /**
     * 导航菜单
     * @param string $ul_attr ul属性可以是class id
     * @param string $li_attr li属性可以是class id
     * @param string $ul_attr2
     * @param string $li_attr2
     * @param string $split 分割符  例如 ->首页  $split 栏目
     * @param string $position 位置或者说标签
     * @usage {:W('Widget/mainMenu')}
     */
    public function mainMenu($ul_attr = 'class="navigation"', $li_attr = '', $ul_attr2 = '', $li_attr2 = '', $split = '', $position = 'head')
    {


        if (S("Widget_mainMenu") == null) {

            $Menu = new MenuLogic();
            $home_menu = $Menu->genMenu($position, $ul_attr, $li_attr, $ul_attr2, $li_attr2, $split);

            $this->assign('home_menu', $home_menu);
            $menu = $this->fetch('Widget:mainMenu');

            S("Widget_mainMenu", $menu, DEFAULT_EXPIRES_TIME);
            echo $menu;

        } else {

            echo S("Widget_mainMenu");

        }


    }
}