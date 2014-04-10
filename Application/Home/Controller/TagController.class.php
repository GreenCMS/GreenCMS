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
 * 标签控制器
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
     * 查询指定标签的详细信息
     * @param $info  detail 查询的 id 或者slug
     */
    public function detail($info)
    {
        $Tag = new TagsLogic();
        $Posts = new PostsLogic();

        $tag = $Tag->detail($info);

        $this->if404($tag, "非常抱歉，没有这个标签，可能它已经躲起来了");

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
        $this->assign('breadcrumbs', get_breadcrumbs('tags',$tag['tag_id']));

        $this->display('Archive/single-list');

    }


}