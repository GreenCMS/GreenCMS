<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: FeedController.class.php
 * User: Timothy Zhang
 * Date: 14-1-23
 * Time: 下午6:52
 */

namespace Home\Controller;

use Common\Logic\PostsLogic;
use Common\Util\Rss;

/**
 * 站点Feed功能
 * Class FeedController
 * @package Home\Controller
 */
class FeedController extends HomeBaseController
{

    /**
     * 初始化 判断feed功能是否开启
     * @param null
     */
    function __construct()
    {
        parent::__construct();

        if (get_opinion('feed_open') == 0) {
            $this->error404("Feed功能关闭");
        }
    }

    /**
     * 默认显示文章feed
     * @param null
     */
    public function index()
    {
        $this->listPost();
    }

    /**
     * 文章feed
     * @param string $type 文章类型
     * @internal param $null 显示数量由 feed_num决定* 显示数量由 feed_num决定
     */
    public function listPost($type = 'single')
    {

        $PostsList = new PostsLogic();
        $post_list = $PostsList->getList(get_opinion('feed_num'), $type, 'post_date desc', true);
        $RSS = new RSS (get_opinion('title'), '', get_opinion('description'), ''); // 站点标题的链接
        foreach ($post_list as $list) {
            $RSS->addItem(
                $list ['post_title'],
                'http://' . $_SERVER["SERVER_NAME"] . get_post_url($list),
                $list ['post_content'],
                $list ['post_date']);
        }

        $RSS->display();
    }

}