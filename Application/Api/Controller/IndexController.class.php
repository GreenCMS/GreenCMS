<?php
/**
 * Created by Green Studio.
 * File: IndexController.php
 * User: TianShuo
 * Date: 14-4-22
 * Time: 下午5:58
 */
/**
 * Created by Green Studio.
 * File: IndexController.php
 * User: TianShuo
 * Date: 14-4-22
 * Time: 下午5:58
 */

namespace Api\Controller;

use Common\Logic\CatsLogic;
use Common\Logic\PostsLogic;
use Common\Logic\TagsLogic;
use Common\Util\File;
use Common\Util\GreenPage;

/**
 * Class IndexController
 * @package Api\Controller
 */
class IndexController extends ApiBaseController
{

    /**
     * 构造函数
     */
    function __construct()
    {
        if (get_opinion('api_open', true, 1) == 0) {
            $this->jsonReturn(0, "API功能关闭");
        }


        parent::__construct();
    }


    /**
     * 最新文章
     */
    public function latest()
    {
        $PostsLogic = new PostsLogic();
        $count = $PostsLogic->countAll('single'); // 查询满足要求的总记录数

        $Page = new GreenPage($count, get_opinion('PAGER')); // 实例化分页类 传入总记录数
        $limit = $Page->firstRow . ',' . $Page->listRows; //获取分页信息
        $posts_list = $PostsLogic->getList($limit, 'single', 'post_date desc', true);

        $res_array["posts"] = array();
        foreach ($posts_list as $post) {
            $post_temp = array();
            $post_temp["post_id"] = $post["post_id"];
            $post_temp["post_title"] = $post["post_title"];
            $post_temp["post_date"] = $post["post_date"];
            $post_temp["post_content"] = mb_substr(strip_tags(str_replace("&nbsp;", "", $post["post_content"])), 0, 200, 'utf-8');
            $post_temp['url'] = U('Api/Index/post', array('id' => $post['post_id']), false, true);

            array_push($res_array["posts"], $post_temp);
        }

        $this->jsonReturn(1, $res_array);

    }

    /**
     * 文章详细
     * @param int $id 文章id
     */
    public function post($id = -1)
    {
        $Posts = new PostsLogic();
        $post_res = $Posts->detail($id, true);

        $res_array = array();
        if (!$post_res) {
            $this->jsonReturn(0, '没有找到文章');
        } else {
            $post_res['post_content'] = strip_tags($post_res['post_content']);
            $post_res['url'] = U('Api/Index/post', array('id' => $post_res['post_id']), false, true);

            $this->jsonReturn(1, $post_res);
        }

    }


    /**
     * 文章归档
     * @param string $key 搜索需要的关键字
     */
    public function archive($key = '')
    {
        $map['post_date'] = array('like', I('get.year', '%') . '-' . I('get.month', '%') . '-' . I('get.day', '%') . '%');
        $info['post_content|post_title'] = array('like', "%$key%");

        $PostsList = new PostsLogic();
        $count = $PostsList->countAll('single', $map); // 查询满足要求的总记录数

        if ($count != 0) {
            $Page = new GreenPage($count, C('PAGER')); // 实例化分页类 传入总记录数
            $limit = $Page->firstRow . ',' . $Page->listRows; //获取分页信息
            $res = $PostsList->getList($limit, 'single', 'post_date desc', true, $map);

            foreach ($res as $key => $value) {
                $res[$key]['post_content'] = strip_tags($res[$key]['post_content']);
                $res[$key]['url'] = U('Api/Index/post', array('id' => $res[$key]['post_id']), false, true);

            }
            $res_array["posts"] = $res;
            $this->jsonReturn(1, $res_array);

        } else {
            $res_array["posts"] = "没有文章";
            $this->jsonReturn(0, $res_array);

        }

    }


    /**
     * 分类文章
     * @param int $id 分类id
     */
    public function cat($id)
    {
        $Cat = new CatsLogic();
        $Posts = new PostsLogic();
        $cat = $Cat->detail($id);
        $posts_id = $Cat->getPostsId($cat['cat_id']);
        $count = sizeof($posts_id);
        if (!empty($posts_id)) {
            $Page = new GreenPage($count, get_opinion('PAGER'));
            $limit = $Page->firstRow . ',' . $Page->listRows;
            $res = $Posts->getList($limit, 'single', 'post_date desc', true, array(), $posts_id);
            foreach ($res as $key => $value) {
                $res[$key]['post_content'] = strip_tags($res[$key]['post_content']);
                $res[$key]['url'] = U('Api/Index/post', array('id' => $res[$key]['post_id']), false, true);

            }

            $res_array["posts"] = $res;
            $this->jsonReturn(1, ($res_array));
        } else {
            $res_array["detail"] = "没有文章";
            $this->jsonReturn(0, ($res_array));
        }


    }

    /**
     * 标签文章
     * @param int $id 指定标签的文章
     */
    public function tag($id)
    {
        $Tag = new TagsLogic();
        $Posts = new PostsLogic();
        $tag = $Tag->detail($id);
        $posts_id = $Tag->getPostsId($tag['cat_id']);
        $count = sizeof($posts_id);
        if (!empty($posts_id)) {
            $Page = new GreenPage($count, get_opinion('PAGER'));
            $limit = $Page->firstRow . ',' . $Page->listRows;
            $res = $Posts->getList($limit, 'single', 'post_date desc', true, array(), $posts_id);

            foreach ($res as $key => $value) {
                $res[$key]['post_content'] = strip_tags($res[$key]['post_content']);
                $res[$key]['url'] = U('Api/Index/post', array('id' => $res[$key]['post_id']), false, true);

            }
            $res_array["posts"] = $res;

            $this->jsonReturn(1, ($res_array));
        } else {
            $res_array["detail"] = "没有文章";
            $this->jsonReturn(0, ($res_array));
        }

    }
}