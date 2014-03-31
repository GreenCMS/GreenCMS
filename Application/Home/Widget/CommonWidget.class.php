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
 * Class CommonWidget
 * @package Home\Widget
 */
class CommonWidget extends Controller
{
    /**
     * @param string $ul_attr
     * @param string $li_attr
     * @param string $split
     */
    public function mainMenu($ul_attr = 'class="navigation"', $li_attr = '', $split = '')
    {

        $Menu = new MenuLogic();
        $home_menu = $Menu->genMenu('head', $ul_attr, $li_attr, $split);

        $this->assign('home_menu', $home_menu);

        $this->display('Widget:mainMenu');

    }
}