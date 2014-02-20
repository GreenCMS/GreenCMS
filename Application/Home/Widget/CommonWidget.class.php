<?php
/**
 * Created by Green Studio.
 * File: CommonWidget.class.php
 * User: TianShuo
 * Date: 14-1-24
 * Time: 下午2:52
 */

namespace Home\Widget;

use Common\Logic\CatsLogic;
use Common\Logic\PostsLogic;
use Common\Logic\TagsLogic;
use Home\Logic\MenuLogic;
use Think\Controller;

class CommonWidget extends Controller
{
    public function mainMenu($ul_attr='class="navigation"',$li_attr='')
    {
//        $TagList = new TagsLogic();
//        $CatList = new CatsLogic();
//        $PageList = new PostsLogic();
//
//        $cat_res = $CatList->category();
//        $tag_res = $TagList->getList(15, false);
//        $page_res = $PageList->getList(20, 'page', 'post_id desc', false);
//        $post_res = $PageList->getList(20, 'single', 'post_id desc', false);
//
//        $this->assign('tags', $tag_res);
//        $this->assign('cats', $cat_res);
//        $this->assign('pages', $page_res);
//        $this->assign('posts', $post_res);


        $Menu = new MenuLogic();
        $home_menu = $Menu->genMenu('head',$ul_attr,$li_attr);

        $this->assign('home_menu',$home_menu);

        $this->display('Widget:mainMenu');

    }
}