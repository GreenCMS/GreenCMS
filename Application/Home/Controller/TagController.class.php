<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: TagController.class.php
 * User: Timothy Zhang
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
        $TagsLogic = new TagsLogic();
        $PostsLogic = new PostsLogic();

        $tag = $TagsLogic->detail($info);

        $this->if404($tag, "非常抱歉，没有这个标签，可能它已经躲起来了");

        $posts_id = $TagsLogic->getPostsId($tag['tag_id']);
        $count = sizeof($posts_id);

        ($count == 0) ? $res404 = 0 : $res404 = 1;
        if (!empty($posts_id)) {
            $Page = new GreenPage($count, get_opinion('PAGER'));
            $pager_bar = $Page->show();
            $limit = $Page->firstRow . ',' . $Page->listRows;

            $posts_list = $PostsLogic->getList($limit, 'single', 'post_date desc', true, array(), $posts_id);
        }

        $this->assign('title', '标签 ' . $tag['tag_name'] . ' 所有文章'); // 赋值数据集
        $this->assign('res404', $res404);
        $this->assign('postslist', $posts_list); // 赋值数据集
        $this->assign('pager', $pager_bar); // 赋值分页输出
        $this->assign('breadcrumbs', get_breadcrumbs('tags', $tag['tag_id']));

        $this->display('Archive/single-list');

    }


    /**
     * //TODO 未知类型TAG显示
     * @param $method 魔术方法名称 即Tag模板类型
     * @param $args
     */
    public function _empty($method, $args)
    {


    }
}