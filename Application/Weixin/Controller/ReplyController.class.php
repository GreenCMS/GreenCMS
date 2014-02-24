<?php
/**
 * Created by Green Studio.
 * File: ReplyController.class.php
 * User: TianShuo
 * Date: 14-2-20
 * Time: 下午5:31
 */

namespace Weixin\Controller;


class ReplyController extends WeixinBaseController
{

    public function index()
    {
        $weixinre = D('Weixinre')->select();

        $this->assign('weixinre', $weixinre);

        $this->display();
    }

    public function text()
    {
        $this->assign('form_action', U('Weixin/Reply/textAddHandle'));
        $this->assign('action_name', '添加');

        $this->display();

    }

    public function textAddHandle()
    {
        $data = I('post.');
        $data['type'] = "text";
        $res = D('Weixinre')->data($data)->add();

        if ($res) {
            $this->success('添加成功', 'Weixin/Reply/index');
        } else {
            $this->error('添加失败');
        }
    }

    public function textEdit($id)
    {

        $this->assign('action', '编辑回复');
        $this->assign('action_url', U('Weixin/Reply/textEdit', array('id' => $id)));

        $item = D('Weixinre')->where(array('wx_re_id' => $id))->find();
        $this->assign('item', $item);
        $this->assign('form_action', U('Weixin/Reply/textEditHandle', array('id' => $id)));

        $this->assign('action_name', '编辑');
        $this->display('Reply/text');
    }

    public function textEditHandle($id)
    {
        $data = I('post.');
        $data['type'] = "text";
        $res = D('Weixinre')->where(array('wx_re_id' => $id))->data($data)->save();

        if ($res) {
            $this->success('编辑成功', 'Weixin/Reply/index');
        } else {
            $this->error('编辑失败或者是没有改变');
        }
    }


    public function pic()
    {

        $this->assign('form_action', U('Weixin/Reply/picAddHandle'));
        $this->assign('action_name', '添加');
        $this->display();

    }

    public function picAddHandle()
    {
        $data = I('post.');
        $data['type'] = "image";
        $res = D('Weixinre')->data($data)->add();

        if ($res) {
            $this->success('添加成功', 'Weixin/Reply/index');
        } else {
            $this->error('添加失败');
        }

    }


    public function news()
    {
        $this->assign('form_action', U('Weixin/Reply/newsAddHandle'));
        $this->assign('action_name', '添加');
        $this->display();

    }

    public function newsEdit($id)
    {
        $this->assign('action', '编辑回复');
        $this->assign('action_url', U('Weixin/Reply/textEdit', array('id' => $id)));

        $item = D('Weixinre')->where(array('wx_re_id' => $id))->find();
        $this->assign('item', $item);
        $this->assign('form_action', U('Weixin/Reply/newsEditHandle', array('id' => $id)));

        $this->assign('action_name', '编辑');
        $this->display('Reply/news');

    }


    public function newsEditHandle($id)
    {
        $data = I('post.');
        $data['type'] = "news";
        $res = D('Weixinre')->where(array('wx_re_id' => $id))->data($data)->save();

        if ($res) {
            $this->success('编辑成功', 'Weixin/Reply/index');
        } else {
            $this->error('编辑失败或者是没有改变');
        }

    }

    public function newsAddHandle()
    {
        $data = I('post.');
        $data['type'] = "news";
        $res = D('Weixinre')->data($data)->add();

        if ($res) {
            $this->success('添加成功', 'Weixin/Reply/index');
        } else {
            $this->error('添加失败');
        }

    }

    public function del($id)
    {
        $res = D('Weixinre')->where(array('wx_re_id' => $id))->delete();

        if ($res) {
            $this->success('删除成功', 'Weixin/Reply/index');
        } else {
            $this->error('删除失败');
        }

    }

}