<?php
/**
 * Created by Green Studio.
 * File: RuleController.class.php
 * User: TianShuo
 * Date: 14-2-20
 * Time: 下午8:34
 */

namespace Weixin\Controller;


class RuleController extends WeixinBaseController
{


    public function index()
    {

        $weixin_button = getMenuButtons();
        $weixinaction = D('Weixinaction')->select();

        foreach ($weixinaction as $key => $value) {
            if ($weixinaction[$key]['action_type'] == 'click') {
                $weixinaction[$key]['action_name'] = $weixin_button[$weixinaction[$key]['action_name']];
            }
        }
        $this->assign('weixinaction', $weixinaction);

        $this->display();

    }

    public function add()
    {


        $weixin_button = getMenuButtons();

        $buttom_option = '';
        foreach ($weixin_button as $key => $value) {
            $buttom_option .= '<option value="' . $key . '"';
            $buttom_option .= '>' . $value . '</option>';

        }

        $this->assign('buttom_option', $buttom_option);
        $this->assign('form_action', U('Weixin/Rule/addHandle'));
        $this->assign('action_name', '添加');

        $this->display();
    }

    public function edit($id)
    {

        $this->assign('action', '编辑规则');
        $this->assign('action_url', U('Weixin/Rule/edit', array('id' => $id)));

        $item = D('Weixinaction')->where(array('wx_action_id' => $id))->find();


        $weixin_button = getMenuButtons();

        $buttom_option = '';
        foreach ($weixin_button as $key => $value) {
            $buttom_option .= '<option value="' . $key . '"';
            if ($item['action_type'] == 'click' && $item['action_name'] == $key) {
                $buttom_option .= 'selected="selected"';
            }
            $buttom_option .= '>' . $value . '</option>';
        }

        $this->assign('buttom_option', $buttom_option);

        $this->assign('item', $item);
        $this->assign('form_action', U('Weixin/Rule/editHandle', array('id' => $id)));

        $this->assign('action_name', '编辑');
        $this->display('Rule/add');
    }

    public function editHandle($id)
    {
        $data = I('post.');
        if ($data['action_type'] == 'text') {
            $data['action_name'] = $data['action_name_keyword'];
            unset($data['action_name_keyword']);
        } elseif ($data['action_type'] == 'click') {
            $data['action_name'] = $data['action_name_key'];
            unset($data['action_name_key']);
        }


        $res = D('Weixinaction')->where(array('wx_action_id' => $id))->data($data)->save();

        if ($res) {
            $this->success('编辑成功');
        } else {
            $this->error('编辑失败或者是没有改变');
        }

    }

    public function addHandle()
    {
        $data = I('post.');
        if ($data['action_type'] == 'text') {
            $data['action_name'] = $data['action_name_keyword'];
            unset($data['action_name_keyword']);
        } elseif ($data['action_type'] == 'click') {
            $data['action_name'] = $data['action_name_key'];
            unset($data['action_name_key']);
        }

        $res = D('Weixinaction')->data($data)->add();

        if ($res) {
            $this->success('添加成功', U('Weixin/Rule/index'));
        } else {
            $this->error('添加失败');
        }

    }


    public function del($id)
    {

        $res = D('Weixinaction')->where(array('wx_action_id' => $id))->delete();

        if ($res) {
            $this->success('删除成功', U('Weixin/Rule/index'));
        } else {
            $this->error('删除失败');
        }


    }

}