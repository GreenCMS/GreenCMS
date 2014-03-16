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
        $data = I('post.', '', null);
        $data['type'] = "text";
        $res = D('Weixinre')->data($data)->add();

        if ($res) {
            $this->success('添加成功', U('Weixin/Reply/index'));
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
            $this->success('编辑成功', U('Weixin/Reply/index'));
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

        $config = array(
            "savePath"   => (Upload_PATH . 'Weixin/' . date('Y') . '/' . date('m') . '/'),
            "maxSize"    => 30000, // 单位KB
            "allowFiles" => array(".jpg")
        );

        $upload = new \Common\Util\Uploader ("img", $config);

        $info = $upload->getFileInfo();
        $img_url = "http://" . $_SERVER['SERVER_NAME'] . str_replace('index.php', '', __APP__) . $info['url'];

        if ($info["state"] != "SUCCESS") { // 上传错误提示错误信息
            $this->error('上传失败' . $info['state']);
        } else {

        }

        $image = new \Think\Image();
        $image->open(WEB_ROOT . $info['url']);
        $image->thumb(150, 150)->save(WEB_ROOT . $info['url']);
        $ACCESS_TOKEN = $this->getAccess();

        $post_data = array(
            "media" => '@' . WEB_ROOT . $info['url']
        );

        $URL = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$ACCESS_TOKEN&type=image";
        $res = json_decode(simple_post($URL, $post_data), true);


        if ($res['errcode'] != '') {
            $this->error('上传失败' . $res['errmsg']);
        } else {

            $data['type'] = $res['type'];
            $data['mediaId'] = $res['media_id'];
            $data['picurl'] = $img_url;
            $res = D('Weixinre')->data($data)->add();

            if ($res) {
                $this->success('上传成功！', U('Weixin/Reply/index'));
            }

//
        }


    }


    public function news()
    {
        $this->assign('imgurl', __ROOT__ . '/Public/share/img/no+image.gif');
        $this->assign('form_action', U('Weixin/Reply/newsAddHandle'));
        $this->assign('action_name', '添加');
        $this->display();

    }

    public function newsEdit($id)
    {
        $this->assign('action', '编辑回复');
        $this->assign('action_url', U('Weixin/Reply/textEdit', array('id' => $id)));

        $item = D('Weixinre')->where(array('wx_re_id' => $id))->find();
        $this->assign('imgurl', $item['picurl']);

        $this->assign('item', $item);
        $this->assign('form_action', U('Weixin/Reply/newsEditHandle', array('id' => $id)));

        $this->assign('action_name', '编辑');
        $this->display('Reply/news');

    }


    public function newsEditHandle($id)
    {


        $data = I('post.', '', null);
        $data['type'] = "news";
        if ($_FILES['img']['size'] != 0) {
            $config = array(
                "savePath"   => (Upload_PATH . 'Weixin/' . date('Y') . '/' . date('m') . '/'),
                "maxSize"    => 300000, // 单位KB
                "allowFiles" => array(".jpg", ".png")
            );

            $upload = new \Common\Util\Uploader ("img", $config);

            $info = $upload->getFileInfo();
            $img_url = "http://" . $_SERVER['SERVER_NAME'] . str_replace('index.php', '', __APP__) . $info['url'];

            if ($info["state"] != "SUCCESS") { // 上传错误提示错误信息
                $this->error('上传失败' . $info['state']);
            } else {
                $data['picurl'] = $img_url;
            }
        }


        $res = D('Weixinre')->where(array('wx_re_id' => $id))->data($data)->save();

        if ($res) {
            $this->success('编辑成功', U('Weixin/Reply/index'));
        } else {
            $this->error('编辑失败或者是没有改变');
        }

    }

    public function newsAddHandle()
    {
        $data = I('post.');
        $data['type'] = "news";
//        if(!empty($_FILES['img'])){
        $config = array(
            "savePath"   => (Upload_PATH . 'Weixin/' . date('Y') . '/' . date('m') . '/'),
            "maxSize"    => 300000, // 单位KB
            "allowFiles" => array(".jpg", ".png")
        );

        $upload = new \Common\Util\Uploader ("img", $config);

        $info = $upload->getFileInfo();
        $img_url = "http://" . $_SERVER['SERVER_NAME'] . str_replace('index.php', '', __APP__) . $info['url'];

        if ($info["state"] != "SUCCESS") { // 上传错误提示错误信息
            $this->error('上传失败' . $info['state']);
        } else {
            $data['picurl'] = $img_url;
        }
//        }

        $res = D('Weixinre')->data($data)->add();

        if ($res) {
            $this->success('添加成功', U('Weixin/Reply/index'));
        } else {
            $this->error('添加失败');
        }

    }

    public function del($id)
    {
        $res = D('Weixinre')->where(array('wx_re_id' => $id))->delete();

        if ($res) {
            $this->success('删除成功', U('Weixin/Reply/index'));
        } else {
            $this->error('删除失败');
        }

    }

}