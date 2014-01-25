<?php
/**
 * Created by Green Studio.
 * File: PostController.class.php
 * User: TianShuo
 * Date: 14-1-23
 * Time: 下午5:02
 */

namespace Home\Controller;


/**
 * Class PostController
 * @package Home\Controller
 */
class PostController extends HomeBaseController
{

    /**
     * @function 文章单页
     * @param $info
     */
    public function single($info = -1)
    {
        $Posts = D('Posts', 'Logic');
        $post_res = $Posts->detail($info);

        $Posts->viewInc($post_res['post_id']);

        $this->assign('post', $post_res); // 赋值数据集
        //print_array($post_res);
        $this->display ( 'single' );

    }

    /**
     * @function 页面单页
     * @param $info
     */
    public function page($info = -1)
    {

        $Posts = D('Posts', 'Logic');
        $post_res = $Posts->detail($info);

        $Posts->viewInc($post_res['post_id']);

        $this->assign('post', $post_res); // 赋值数据集
        //print_array($post_res);
        $this->display ( 'page' );

    }

    /**
     * @function 未知类型单页
     */
    public function _empty()
    {
        //ACTION_NAME

        //TODO 通用模板机制
        // $this->page( $_GET['info'] );


    }


}