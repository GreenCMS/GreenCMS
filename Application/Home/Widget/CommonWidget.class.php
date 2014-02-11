<?php
/**
 * Created by Green Studio.
 * File: CommonWidget.class.php
 * User: TianShuo
 * Date: 14-1-24
 * Time: 下午2:52
 */

namespace Home\Widget;

use Think\Controller;
use Common\Logic\PostsLogic;
use Common\Logic\TagsLogic;
use Common\Logic\CatsLogic;

class CommonWidget extends Controller
{
    public function mainMenu()
    {
        $TagList = new TagsLogic();
        $CatList = new CatsLogic();
        $PageList = new PostsLogic();

        $cat_res = $CatList->category();
        $tag_res = $TagList->getList(15, false);
        $page_res = $PageList->getList(20, 'page', 'post_id desc', false);
        $post_res = $PageList->getList(20, 'single', 'post_id desc', false);

        $this->assign('tags', $tag_res);
        $this->assign('cats', $cat_res);
        $this->assign('pages', $page_res);
        $this->assign('posts', $post_res);

        $this->display('Widget:mainMenu');

    }
}