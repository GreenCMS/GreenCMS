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

class MenuLogic extends Model
{


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

    public function genMenu($menu_position = 'head')
    {

        $menu = $this->getMenu('head');

        $res = '<ul class="navigation">';
        foreach ($menu as $key => $value) {
            $class = '';
            if (!empty($value['menu_children'])) {
                $class = 'class="arrow"';
            }
            $res .= ' <li><a ' . $class . ' target="' . $value['menu_action'] . '" href="' . $value['menu_abs_url'] . '">' . $value['menu_name'] . '</a>';

            if (!empty($value['menu_children'])) {
                $res .= '<ul>';

                foreach (array_sort($value['menu_children'], 'menu_sort') as $key => $value) {
                    $res .= ' <li><a target="' . $value['menu_action'] . '" href="' . $value['menu_abs_url'] . '">' . $value['menu_name'] . '</a></li>';

                }
                $res .= '</ul>';
            }


            $res .= '</li>';
        }

        $res .= '</ul>';
        return $res;

    }


}