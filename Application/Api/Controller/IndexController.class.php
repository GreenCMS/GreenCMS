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

use Common\Logic\PostsLogic;
use Common\Util\File;

class IndexController extends ApiBaseController
{

    public function latest()
    {
        $PostsList = new PostsLogic();

        $posts_res = $PostsList->getList(10, 'single', 'post_id desc', true);
//
//        dump($posts_res);
        $res_array["posts"] = array();
        foreach ($posts_res as $post) {
            $temp = array();
            $temp["post_id"] = $post["post_id"];
            $temp["post_title"] = $post["post_title"];
            $temp["post_date"] = $post["post_date"];


            $temp["post_content"] = mb_substr(strip_tags(str_replace("&nbsp;", "", $post["post_content"])), 0, 200, 'utf-8');
            $temp['url'] = U('Api/Post/single', array('id' => $post['post_id']), false, true);

            array_push($res_array["posts"], $temp);
        }

//        dump($res);
        die(json_encode($res_array));


    }


}