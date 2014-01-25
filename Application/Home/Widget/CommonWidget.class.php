<?php
/**
 * Created by Green Studio.
 * File: CommonWidget.class.php
 * User: TianShuo
 * Date: 14-1-24
 * Time: 下午2:52
 */

namespace Home\Widget;

use Home\Controller\HomeBaseController;

class CommonWidget extends HomeBaseController
{
    public function mainMenu()
    {

        $CatList = D('Cats', 'Logic');
        //$cat_res = $CatList->getList(15, false);
        $cat_res = $CatList->category();

        $TagList = D('Tags', 'Logic');
        $tag_res = $TagList->getList(15, false);

        $PageList = D('Posts', 'Logic');

        $page_res = $PageList->getList(20, 'page', 'post_id desc', true);
        $post_res = $PageList->getList(20, 'single', 'post_id desc', true);;

        $this->assign('tags', $tag_res);
        $this->assign('cats', $cat_res);
        $this->assign('pages', $page_res);
        $this->assign('posts', $post_res);

        $this->display('Widget:mainMenu');

    }
}