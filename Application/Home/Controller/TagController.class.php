<?php
/**
 * Created by Green Studio.
 * File: TagController.class.php
 * User: TianShuo
 * Date: 14-1-23
 * Time: 下午7:30
 */

namespace Home\Controller;
use Common\Logic\PostsLogic;
use Common\Logic\TagsLogic;
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
        $Tag = new TagsLogic();
        $Posts = new PostsLogic();

        $tag = $Tag->detail($info);

        if (empty($tag)) $this->error404("非常抱歉，没有这个标签，可能它已经躲起来了");

        $posts_id = $Tag->getPostsId($tag['tag_id']);
        $count = sizeof($posts_id);

        ($count == 0) ? $res404 = 0 : $res404 = 1;
        if (!empty($posts_id)) {
            $Page = new GreenPage($count, get_opinion('PAGER'));
            $pager_bar = $Page->show();
            $limit = $Page->firstRow . ',' . $Page->listRows;

            $res = $Posts->getList($limit, 'single', 'post_id desc', true, array(), $posts_id);
        }

        $this->assign('title', $tag['tag_name']); // 赋值数据集
        $this->assign('res404', $res404);
        $this->assign('postslist', $res); // 赋值数据集
        $this->assign('pager', $pager_bar); // 赋值分页输出

        $this->display('Archive/single-list');

    }


}