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

class CommonWidget extends Controller
{
    public function mainMenu($ul_attr = 'class="navigation"', $li_attr = '')
    {

        $Menu = new MenuLogic();
        $home_menu = $Menu->genMenu('head', $ul_attr, $li_attr);

        $this->assign('home_menu', $home_menu);

        $this->display('Widget:mainMenu');

     }
}