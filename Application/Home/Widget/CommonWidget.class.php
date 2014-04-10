<?php
/**
 * Created by Green Studio.
 * File: CommonWidget.class.php
 * User: TianShuo
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
     * @param string $split 分割符  例如 ->首页  $split 栏目
     * @param string $position 位置或者说标签
     * @usage {:W('Widget/mainMenu')}
     */
    public function mainMenu($ul_attr = 'class="navigation"', $li_attr = '', $split = '',$position='head')
    {

        $Menu = new MenuLogic();
        $home_menu = $Menu->genMenu($position, $ul_attr, $li_attr, $split);

        $this->assign('home_menu', $home_menu);

        $this->display('Widget:mainMenu');

    }
}