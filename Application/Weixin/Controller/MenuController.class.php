<?php
/**
 * Created by Green Studio.
 * File: MenuController.php
 * User: TianShuo
 * Date: 14-2-20
 * Time: 下午5:37
 */

namespace Weixin\Controller;

use Weixin\Event\MenuEvent;

class MenuController extends WeixinBaseController
{
    public function index()
    {
        $Menu = new MenuEvent();
        $Weixin_menu = json_decode(trim(C('Weixin_menu')), true);
        $Weixin_menu = $Weixin_menu['button'];

        $this->assign('weixin_menu', $Weixin_menu);

        $this->display();
    }


    public function edit($level = 1, $pid = 0, $id = 0, $type = 'empty')
    {
        $this->assign('form_action', U('Weixin/Menu/editHandle', array('level' => $level, 'pid' => $pid, 'id' => $id, 'type' => $type)));
        $this->assign('action', '编辑菜单');
        $this->assign('action_name', '编辑');
        $this->assign('action_url', U('Weixin/Menu/edit', array('level' => $level, 'pid' => $pid, 'id' => $id, 'type' => $type)));

        $Weixin_menu = json_decode(trim(C('Weixin_menu')), true);
        $Weixin_menu = $Weixin_menu['button'];

        $this->assign('weixin_menu', $Weixin_menu);

        if ($level == 1 && $pid == $id) {
            $this->assign('weixin_menu_now', $Weixin_menu[$pid]);
        } elseif ($level == 2) {
            $this->assign('weixin_menu_now', $Weixin_menu[$pid]["sub_button"][$id]);
        }

        $this->display('edit_' . $type);

    }

    public function editHandle($level = 1, $pid = 0, $id = 0, $type = 'empty')
    {

        $Weixin_menu = json_decode(trim(C('Weixin_menu')), true);

        if ($level == 1 && $pid == $id && $type = 'empty') {
            $Weixin_menu["button"][$pid]['name'] = I('post.name');
        } elseif ($level == 2 && $type == 'click') {
            $Weixin_menu["button"][$pid]["sub_button"][$id]['name'] = I('post.name');
            $Weixin_menu["button"][$pid]["sub_button"][$id]['key'] = I('post.key');

        } elseif ($level == 2 && $type == 'view') {
            $Weixin_menu["button"][$pid]["sub_button"][$id]['name'] = I('post.name');
            $Weixin_menu["button"][$pid]["sub_button"][$id]['url'] = I('post.url');
        }

        $menu_json = decodeUnicode(json_encode($Weixin_menu));
        $Menu = new MenuEvent();
        $res = $Menu->save($menu_json);

        if ($res) {
            $this->success('设置成功');
        } else {
            $this->error('设置失败或者没有改变');
        }


    }

    public function additem($level = 1, $pid = 0, $id = 0, $type = 'empty')
    {
        $this->assign('form_action', U('Weixin/Menu/additemHandle', array('level' => $level, 'pid' => $pid, 'id' => $id, 'type' => $type)));
        $this->assign('action', '添加菜单项');
        $this->assign('action_name', '添加');
        $this->assign('action_url', U('Weixin/Menu/additem', array('level' => $level, 'pid' => $pid, 'id' => $id, 'type' => $type)));


        $this->display('edit_full');


    }

    public function additemHandle($level = 1, $pid = 0, $id = 0, $type = 'empty')
    {


        $Menu = new MenuEvent();
        $Weixin_menu = json_decode(trim(C('Weixin_menu')), true);


        if ($level == 1 && $pid == $id && I('post.reply_type') == 'empty') {
            if (sizeof($Weixin_menu['button']) == 3) {
                $this->error('此层菜单已满');
            } else {
                $temp_array = array();
                $temp_array['name'] = I('post.name');
                $temp_array['sub_button'] = array();
                array_insert($Weixin_menu["button"], $pid + 1, $temp_array);
            }
        } else if ($level == 1 && $pid == $id && I('post.reply_type') == 'click') {
            $temp_array = array();
            $temp_array['name'] = I('post.name');
            $temp_array['type'] = 'click';
            $temp_array['key'] = I('post.key');
            array_insert($Weixin_menu["button"][$pid]["sub_button"], 0, $temp_array);

        } else if ($level == 1 && $pid == $id && I('post.reply_type') == 'view') {
            $temp_array = array();
            $temp_array['name'] = I('post.name');
            $temp_array['type'] = 'view';
            $temp_array['url'] = I('post.url');
            array_insert($Weixin_menu["button"][$pid]["sub_button"], 0, $temp_array);

        } else if ($level == 2 && I('post.reply_type') == 'empty') {
            $this->error('此层菜单已满');

        } else if ($level == 2 && I('post.reply_type') == 'click') {
            $temp_array = array();
            $temp_array['name'] = I('post.name');
            $temp_array['type'] = 'click';
            $temp_array['key'] = I('post.key');
            array_insert($Weixin_menu["button"][$pid]["sub_button"], $id + 1, $temp_array);

        } else if ($level == 2 && I('post.reply_type') == 'view') {
            $temp_array = array();
            $temp_array['name'] = I('post.name');
            $temp_array['type'] = 'view';
            $temp_array['url'] = I('post.url');
            array_insert($Weixin_menu["button"][$pid]["sub_button"], $id + 1, $temp_array);

        }


        $menu_json = decodeUnicode(json_encode($Weixin_menu));
        $Menu = new MenuEvent();
        $res = $Menu->save($menu_json);

        if ($res) {
            $this->success('设置成功');
        } else {
            $this->error('设置失败或者没有改变');
        }


    }


    public function delitem($level = 1, $pid = 0, $id = 0, $type = 'empty')
    {
        $Menu = new MenuEvent();
        $Weixin_menu = json_decode(trim(C('Weixin_menu')), true);


        if ($level == 1 && $pid == $id) {
            unset($Weixin_menu['button'][$pid]);

        } elseif ($level == 2) {
            unset($Weixin_menu['button'][$pid]["sub_button"][$id]);
        }

        $menu_json = decodeUnicode(json_encode($Weixin_menu));
        $Menu = new MenuEvent();
        $res = $Menu->save($menu_json);

        if ($res) {
            $this->success('设置成功');
        } else {
            $this->error('设置失败或者没有改变');
        }


    }

    public function del()
    {
        $Menu = new MenuEvent();
        $res = ($Menu->delete());
        if ($res) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');

        }
    }

    public function restore()
    {
        $Menu = new MenuEvent();
        $res = $Menu->restore();
        if ($res) {
            $this->success('设置成功');
        } else {
            $this->error('设置失败或者没有改变');

        }
    }


    public function sync()
    {
        $Menu = new MenuEvent();
        $menu_json = $Menu->get();

        $res = $Menu->save($menu_json);
        if ($res) {
            $this->success('设置成功');
        } else {
            $this->error('设置失败或者没有改变');

        }

    }


    public function update()
    {
        $Menu = new MenuEvent();

        $Weixin_menu_decode = json_decode(trim(C('Weixin_menu')), true);
        $menu = decodeUnicode(json_encode($Weixin_menu_decode));

        $res_info = ($Menu->create($menu));

        json_decode($res_info, true);

        if ($res_info['errcode'] == 0) {
            $this->success('更新成功');
        } else {
            $this->error('更新失败' . $res_info['errmsg']);

        }
    }

}