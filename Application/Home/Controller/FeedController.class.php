<?php
/**
 * Created by Green Studio.
 * File: FeedController.class.php
 * User: TianShuo
 * Date: 14-1-23
 * Time: 下午6:52
 */

namespace Home\Controller;
use Common\Controller\BaseController;
use Common\Util\Rss;

class FeedController extends BaseController {
    function __construct() {
        parent::__construct ();
        if( get_opinion( 'feed_open' ) == 0 ){
            //TODO FEED功能关闭
        }
    }

    public function index() {
        $this->listSingle();
    }

    public function listSingle() {

        $PostsList = D( 'Posts', 'Logic' );
        $post_list = $PostsList->getList( get_opinion( 'feed_num' ), 'single', 'post_id desc', true );
        $RSS = new RSS ( get_opinion( 'all_title' ), '', get_opinion( 'all_title' ), '' ); // 站点标题的链接
        foreach ( $post_list as $list ) {
            $RSS->AddItem (
                $list ['post_title'],

                $list ['post_id'] ,

                $list ['post_content'],

                $list ['post_date'] );
        }

        $RSS->Display ();

    }
    public function listsPage(){

        $PostsList = D( 'Posts', 'Logic' );
        $post_list = $PostsList->getList( get_opinion( 'feed_num' ), 'page', 'post_id desc', true );
        $RSS = new RSS ( get_opinion( 'ALL_TITLE' ), '', get_opinion( 'ALL_TITLE' ), '' ); // 站点标题的链接
        foreach ( $post_list as $list ) {
            $RSS->AddItem (
                $list ['post_title'],

                $list ['post_id'] ,

                $list ['post_content'],

                $list ['post_date'] );
        }

        $RSS->Display ();
    }

}