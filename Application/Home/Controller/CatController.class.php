<?php
/**
 * Created by Green Studio.
 * File: CatController.class.php
 * User: TianShuo
 * Date: 14-1-23
 * Time: 下午8:00
 */

namespace Home\Controller;
use Common\Util\GreenPage;


/**
 * Class CatController
 * @package Home\Controller
 */
class CatController extends HomeBaseController
{

    /**
     *
     */
    public function index()
    {

    }

    /**
     * @param $info id
     */
    public function detail($info)
    {
        $Cat = D('Cats', 'Logic');
        $Posts = D('Posts', 'Logic');

        $cat = $Cat->detail($info);
        if (empty($tag)) {
            //TODO error("没有这个标签");
        }

        $posts_id = $Cat->getPostsId($cat['cat_id']);
        $count = sizeof($posts_id);

        if ($count == 0) {
            //TODO 数量为0
        }

        $Page = new GreenPage($count, get_opinion('PAGER'));
        $pager_bar = $Page->show();
        $limit = $Page->firstRow . ',' . $Page->listRows;

        $res = $Posts->getList($limit, 'single', 'post_id desc', true, array(), $posts_id);

        $this->assign('title', $cat['tag_name']); // 赋值数据集

        $this->assign('postslist', $res); // 赋值数据集
        $this->assign('page', $pager_bar); // 赋值分页输出

        //TODO display
        print_array($res);

        // $this->display ( 'Archive/single-list' );

    }
}