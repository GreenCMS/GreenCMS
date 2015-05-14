<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: WidgetWidget.class.php
 * User: Timothy Zhang
 * Date: 14-1-24
 * Time: 上午9:50
 */

namespace Home\Widget;

use Common\Util\Category;
use Home\Logic\MenuLogic;

use Common\Logic\CatsLogic;
use Common\Logic\TagsLogic;
use Think\Controller;

/**
 * Widget
 * Class WidgetWidget
 * @package Home\Widget
 */
class WidgetWidget extends Controller
{

    public function archive()
    {

    }

    public function recentPost()
    {

        $post_list = D('Posts')->cache(true)->order('post_id')->limit(4)->relation(false)->select();

        $this->assign('list', $post_list);

        $this->display('Widget:recentPost');

    }

    /**
     * 搜索框
     * @usage {:W('Widget/search')}
     */
    public function search()
    {

        $this->display('Widget:search');

    }

    /**
     * 关于我们
     * @usage {:W('Widget/aboutUs')}
     */
    public function aboutUs()
    {

        $about_us = get_opinion('widget_about_us');

        $this->assign('widget_about_us', $about_us);
        $this->display('Widget:aboutus');
    }

    /**
     * 所有分类
     * @usage {:W('Widget/categories')}
     */
    public function categories()
    {
        if (S("Widget_categories") == null) {
            $CatList = new CatsLogic();
            $this->assign('list', $CatList->category());
            $categories = $this->fetch('Widget:categories');

            S("Widget_categories", $categories, DEFAULT_EXPIRES_TIME);
            echo $categories;

        } else {

            echo S("Widget_categories");

        }


    }

    /**
     * 所有标签
     * @usage {:W('Widget/tag')}
     */
    public function tag()
    {
        if (S("Widget_tag") == null) {
            $TagList = new TagsLogic();

            $tag_res = $TagList->getList(50, false, true);

            $this->assign('tagClouds', $tag_res);

            $tag = $this->fetch('Widget:tag');

            S("Widget_tag", $tag, DEFAULT_EXPIRES_TIME);
            echo $tag;

        } else {

            echo S("Widget_tag");

        }


    }


    /**
     * 所有链接
     * @usage {:W('Widget/links')}
     */
    public function links()
    {
        $this->display('Widget:links');

    }


    /**
     * 父类与子类分类列表
     * @usage {:W('Widget/catSidebar',array("cat_id"=>$cat_id))}
     */
    public function catSidebar($cat_id = 0, $default_title)
    {

        if ($cat_id == null) {
            $this->assign('cat_sidebar_title', $default_title); // 赋值数据集

            $CatList = new CatsLogic();

            $Cat = new Category ('Cats', array('cat_id', 'cat_father', 'cat_name', 'cat_name'));

            $children['cat_children'] = $Cat->getList();
            foreach ($children['cat_children'] as $key => $value) {
                $children['cat_children'][$key]['cat_children'] = $children['cat_children'][$key];
            }
            $this->assign('children2', $children);


        } else {

            $Cat = new CatsLogic();
            $children = $Cat->getChildren($cat_id);

            if (empty($children['cat_children'])) {
                //无子类处理
                if ($children['cat_father'] == 0) {
                    //无父类
                    $this->assign('cat_sidebar_title', $children["cat_name"]); // 赋值数据集

                } else {
                    //有父类

                    $children2 = $Cat->getChildren($children['cat_father']);
                    $this->assign('cat_sidebar_title', $children2["cat_name"]); // 赋值数据集

                    $this->assign('children2', $children2);

                }


            } else {
                //有子类处理
                $this->assign('cat_sidebar_title', $children["cat_name"]); // 赋值数据集
                $this->assign('children2', $children);


            }
        }

        $this->assign('cat_id', $cat_id); // 赋值数据集
        $this->display('Widget:cat_sidebar');

    }


    public function menuHead($position = 'head') //, $ul_attr='', $li_attr, $ul_attr2, $li_attr2, $split
    {

        $Menu = new MenuLogic();
        $menu = $Menu->getMenu($position);


        $this->assign('home_menu', ($menu));
        $this->display('Widget:menuHead');


    }


}