<?php

namespace Addons\Guestbook\Controller;

use Admin\Controller\AddonsController;
use Common\Util\GreenPage;

class GuestbookController extends AddonsController
{

    public function manage()
    {

        $order = 'date DESC';

        $page = I('get.page', get_opinion('PAGER'));

        $count = count(M('guestbook')->where($where)->select());

        if ($count != 0) {
            $Page = new GreenPage($count, $page); // 实例化分页类 传入总记录数
            $pager_bar = $Page->show();
            $limit = $Page->firstRow . ',' . $Page->listRows;
        }

        $message = M('guestbook')->where($where)->order($order)->limit($limit)->select();

        $this->assign('message', $message);
        $this->assign('action', '留言板管理');
        $this->assign('module', '留言板');
        $this->assign('module_url', U('Admin/Custom/plugin'));

        $this->display(T('Addons://Guestbook@Guestbook/manage'));
    }

    public function delAll()
    {
        $message = I('message');
        // dump($message);die;

        $mod = M('guestbook');

        foreach ($message as $key => $value) {
            $mod->where(array('id' => $value))->delete();
        }

        $this->success('批量删除成功', addons_url('Guestbook://Guestbook/manage'));
    }

    /**
     * 回复留言
     * @return void [type] [description]
     */
    public function reply()
    {

        if (I('reply')) {
            $data = array(
                'id' => I('id', 'intval'),
                'reply' => I('reply', 'htmlspecialchars')
            );
            if (M('guestbook')->save($data)) {
                $this->success('回复成功', addons_url('Guestbook://Guestbook/manage'));
            } else {
                $this->error('回复失败');
            }
        }
    }

    /**
     * 删除留言
     * @return void [type] [description]
     */
    public function del()
    {
        $where['id'] = I('id', 'intval');
        $result = M('guestbook')->where($where)->delete();
        if ($result) {
            $this->success('删除成功', addons_url('Guestbook://Guestbook/manage'));
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 审核留言
     * @return void [type] [description]
     */
    public function approve()
    {
        $id = I('id', 'intval');

        $data['status'] = 1;

        $result = M('guestbook')->where(array('id' => $id))->save($data);

        if ($result) {
            $this->success('审核成功', addons_url('Guestbook://Guestbook/manage'));
        } else {
            $this->error('审核失败');
        }
    }

}