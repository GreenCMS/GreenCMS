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

        $res = $PostsList->getList(20, 'single', 'post_id desc', true);
        foreach ($res as $key => $value) {

            $res[$key] ["post_content"] = mb_substr(strip_tags(str_replace( "&nbsp;","",$res[$key]["post_content"])), 0, 200, 'utf-8');
            $res[$key]['url'] = U('Api/Post/single', array('id' => $value['post_id']), false, true);
        }

//        dump($res);
        die(json_encode($res));


    }


}