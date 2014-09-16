<?php
/**
 * Created by PhpStorm.
 * User: TianShuo
 * Date: 14-9-16
 * Time: 上午8:14
 */

namespace Addons\Guestbook\Controller;
use Home\Controller\AddonsController;

class GuestbookHomeController extends AddonsController{
    /**
     * 添加留言
     */
    public function add()
    {
        if (!I('name') || !I('email') || !I('title') || !I('content')) {
            $this->error('表单未填写完整！');
        } else {

            $post_id = I('post_id');
            $post_type = 'page';
            // dump(I());die;
            $data = array(
                'name' => I('name', 'htmlspecialchars'),
                'email' => I('email', 'htmlspecialchars'),
                'title' => I('title', 'htmlspecialchars'),
                'content' => I('content', 'htmlspecialchars'),
                'date' => date("Y-m-d H:m:s", time()),
                'ip' => get_client_ip(),
                'status' => 1,
            );
            $result = D('guestbook')->add($data);

            if ($result) {
                $this->success('留言成功', U('Home/Post/' . $post_type, array('info' => $post_id)));
            } else {
                $this->error('留言失败');
            }
        }
    }

} 