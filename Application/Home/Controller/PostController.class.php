<?php
/**
 * Created by Green Studio.
 * File: PostController.class.php
 * User: TianShuo
 * Date: 14-1-23
 * Time: 下午5:02
 */

namespace Home\Controller;
use Common\Logic\PostsLogic;


/**
 * Class PostController
 * @package Home\Controller
 */
class PostController extends HomeBaseController
{

    /**
     * @function 文章单页
     *
     * @param $info
     */
    public function single($info = -1)
    {
        $Posts = new PostsLogic();
        $post_res = $Posts->detail($info);

        if (empty($post_res)) $this->error404("非常抱歉，你需要的文章暂时不存在，可能它已经躲起来了。.");
        $Posts->viewInc($post_res['post_id']);

        $this->assign('post', $post_res); // 赋值数据集
        $this->display('single');

    }

    /**
     * @function 页面单页
     *
     * @param $info
     */
    public function page($info = -1)
    {

        $Posts = new PostsLogic();
        $post_res = $Posts->detail($info);
        if (empty($post_res)) $this->error404("非常抱歉，你需要的页面暂时不存在，可能它已经躲起来了。.");

        $Posts->viewInc($post_res['post_id']);

        $this->assign('post', $post_res); // 赋值数据集
        $this->display('page');

    }

    /**
     * @function 未知类型单页
     */
    public function _empty($method, $args)
    {
        //ACTION_NAME
//        dump($method);
//        dump(I('get.'));
        $info = I('get.info');
        //TODO 通用模板机制
        $this->single($info);


    }


}