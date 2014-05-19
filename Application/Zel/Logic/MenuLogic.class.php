<?php
/**
 * Created by Green Studio.
 * File: MenuLogic.php
 * User: TianShuo
 * Date: 14-2-12
 * Time: 下午6:07
 */

namespace Home\Logic;


use Think\Model;

/**
 * Menu逻辑组件
 * Class MenuLogic
 * @package Home\Logic
 */
class MenuLogic extends Model
{


    /**
     * 得到菜单
     * @param string $menu_position
     * @return array
     */
    public function getMenu($menu_position = 'head')
    {
        $home_menu = D('Menu')->where(array('menu_position' => $menu_position))->select();
        $home_menu_res = array();
        foreach ($home_menu as $key => $value) {

            $home_menu[$key]['menu_abs_url'] = getRealURL($value);

            if ($home_menu[$key]['menu_pid'] == 0) {
                $home_menu_res[$home_menu[$key]['menu_id']] = $home_menu[$key];
            }
        }

        foreach ($home_menu as $key => $value) {
            if (empty($home_menu_res[$home_menu[$key]['menu_pid']]['menu_children'])) {
                $home_menu_res[$home_menu[$key]['menu_pid']]['menu_children'] = array();
            }
            if ($home_menu[$key]['menu_pid'] != 0) {
                array_push($home_menu_res[$home_menu[$key]['menu_pid']]['menu_children'], $home_menu[$key]);
            }
        }


        return array_sort($home_menu_res, 'menu_sort');
    }

    /**
     * 获取菜单 <ul> 返回的内容</ul>
     * @param string $menu_position 位置或者说标签
     * @param string $ul_attr ul属性可以是class id
     * @param string $li_attr li属性可以是class id
     * @param string $split 分割符  例如 ->首页  $split 栏目
     * @return string <ul> 返回的内容</ul>
     */
    public function genMenu($menu_position = 'head', $ul_attr = 'class="navigation"', $li_attr = '', $split = '')
    {

        $menu = $this->getMenu($menu_position);

        $res = '<ul ' . $ul_attr . '>';
        foreach ($menu as $key => $value) {
            $class = '';
            if (!empty($value['menu_children'])) {
                $class = 'class="arrow"';
            }
            $res .= ' <li ' . $li_attr . '><a ' . $class . ' target="' . $value['menu_action'] . '" href="' . $value['menu_abs_url'] . '">' . $value['menu_name'] . '</a>';

            if (!empty($value['menu_children'])) {
                $res .= '<ul>';

                foreach (array_sort($value['menu_children'], 'menu_sort') as $key => $value) {
                    $res .= ' <li ' . $li_attr . '><a target="' . $value['menu_action'] . '" href="' . $value['menu_abs_url'] . '">' . $value['menu_name'] . '</a></li>';

                }
                $res .= '</ul>';
            }

            $res .= '</li>';

            $res .= $split;

        }

        $res .= '</ul>';
        return $res;

    }


}