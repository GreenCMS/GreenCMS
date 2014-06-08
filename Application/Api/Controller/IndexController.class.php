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

class IndexController extends ApiBaseController
{

    function __construct()
    {
        if (get_opinion('api_open', false, 1) == 0) {
            $this->jsonReturn(0, "API功能关闭");
        }


        parent::__construct();
    }


    public function latest()
    {
        $PostsList = new PostsLogic();

        $count = $PostsList->countAll('single'); // 查询满足要求的总记录数


        $Page = new GreenPage($count, get_opinion('PAGER')); // 实例化分页类 传入总记录数
        $limit = $Page->firstRow . ',' . $Page->listRows; //获取分页信息
        $posts_res = $PostsList->getList($limit, 'single', 'post_id desc', true);


        //
//        dump($posts_res);
        $res_array["posts"] = array();
        foreach ($posts_res as $post) {
            $temp = array();
            $temp["post_id"] = $post["post_id"];
            $temp["post_title"] = $post["post_title"];
            $temp["post_date"] = $post["post_date"];


            $temp["post_content"] = mb_substr(strip_tags(str_replace("&nbsp;", "", $post["post_content"])), 0, 200, 'utf-8');
            $temp['url'] = U('Api/Index/post', array('id' => $post['post_id']), false, true);

            array_push($res_array["posts"], $temp);
        }

//        dump($res);
        die(json_encode($res_array));


    }

    public function post($id = -1)
    {
        $Posts = new PostsLogic();
        $post_res = $Posts->detail($id, true);

        $res_array = array();
        if (!$post_res) {
            $this->jsonReturn(0);
        } else {
            $post_res['post_content'] = strip_tags($post_res['post_content']);
            $post_res['url'] = U('Api/Index/post', array('id' => $post_res['post_id']), false, true);

            $res_array["detail"] = $post_res;

            $this->jsonReturn(1, $res_array);
        }

    }


    public function archive($key = '')
    {
        $map['post_date'] = array('like', I('get.year', '%') . '-' . I('get.month', '%') . '-' . I('get.day', '%') . '%');
        $info['post_content|post_title'] = array('like', "%$key%");

        $PostsList = new PostsLogic();
        $count = $PostsList->countAll('single', $map); // 查询满足要求的总记录数

        if ($count != 0) {
            $Page = new GreenPage($count, C('PAGER')); // 实例化分页类 传入总记录数
            $limit = $Page->firstRow . ',' . $Page->listRows; //获取分页信息
            $res = $PostsList->getList($limit, 'single', 'post_id desc', true, $map);

            foreach ($res as $key => $value) {
                $res[$key]['post_content'] = strip_tags($res[$key]['post_content']);
                $res[$key]['url'] = U('Api/Index/post', array('id' => $res[$key]['post_id']), false, true);

            }
            $res_array["posts"] = $res;
//            $this->json_return(1, $res_array);
            die(json_encode($res_array));

        } else {
            $res_array["posts"] = "没有文章";
//            $this->json_return(0, $res_array);
            die(json_encode($res_array));


        }

    }


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
            $res = $Posts->getList($limit, 'single', 'post_id desc', true, array(), $posts_id);
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
            $res = $Posts->getList($limit, 'single', 'post_id desc', true, array(), $posts_id);

            foreach ($res as $key => $value) {
                $res[$key]['post_content'] = strip_tags($res[$key]['post_content']);
                $res[$key]['url'] = U('Api/Index/post', array('id' => $res[$key]['post_id']), false, true);

            }


            $res_array["posts"] = $res;
            $this->jsonReturn(1, json_encode($res_array));
        } else {
            $res_array["detail"] = "没有文章";
            $this->jsonReturn(0, json_encode($res_array));
        }

    }
}