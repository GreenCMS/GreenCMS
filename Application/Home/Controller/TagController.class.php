<?php
/**
 * Created by Green Studio.
 * File: TagController.class.php
 * User: TianShuo
 * Date: 14-1-23
 * Time: 下午7:30
 */

namespace Home\Controller;
use Common\Util\GreenPage;


/**
 * Class TagController
 * @package Home\Controller
 */
class TagController extends HomeBaseController
{

    /**
     *
     */
    public function index()
    {

    }

    /**
     * @param $info
     */
    public function detail($info)
    {
        $Tag = D('Tags', 'Logic');
        $Posts = D('Posts', 'Logic');

        $tag = $Tag->detail($info);
        if (empty($tag)) {
            //TODO error("没有这个标签");
        }

        $posts_id = $Tag->getPostsId($tag['tag_id']);
        $count = sizeof($posts_id);

        ($count == 0) ? $res404 = 0 : $res404 = 1;

        $Page = new GreenPage($count, get_opinion('PAGER'));
        $pager_bar = $Page->show();
        $limit = $Page->firstRow . ',' . $Page->listRows;

        $res = $Posts->getList($limit, 'single', 'post_id desc', true, array(), $posts_id);

        $this->assign('title', $tag['tag_name']); // 赋值数据集
        $this->assign('res404', $res404);
        $this->assign('postslist', $res); // 赋值数据集
        $this->assign('page', $pager_bar); // 赋值分页输出

        //TODO display
        // print_array($res);

        $this->display ( 'Archive/single-list' );

    }


}