<?php
/**
 * Created by Green Studio.
 * File: IndexController.class.php
 * User: TianShuo
 * Date: 14-1-11
 * Time: 下午1:40
 */
namespace Home\Controller;

/**
 * Class IndexController
 * @package Home\Controller
 */
class IndexController extends HomeBaseController
{

    /**
     *
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 首页
     */
    public function index()
    {

        /**
         * $this->news = D('Cats', 'Logic')->getPostsByCat(16, 7);
        $this->policy = D('Cats', 'Logic')->getPostsByCat(17, 7);
        $this->tech = D('Cats', 'Logic')->getPostsByCat(18, 7);
        $this->college = D('Cats', 'Logic')->getPostsByCat(19, 7);
        $this->school = D('Cats', 'Logic')->getPostsByCat(20, 7);
         */
        //$this->news = D('Cats', 'Logic')->getPostsByCat(1, 7);
        $this->policy = D('Cats', 'Logic')->getPostsByCat(1, 7);
        $this->tech = D('Cats', 'Logic')->getPostsByCat(1, 7);
        $this->college = D('Cats', 'Logic')->getPostsByCat(1, 7);
        $this->school = D('Cats', 'Logic')->getPostsByCat(1, 7);

        /**
         *
         */
        $this->display();
    }


    function wordpressImport(){
        $Wordpress=new \Common\Event\WordpressEvent();
        $Wordpress->catImport();
        $Wordpress->tagImport();
        $Wordpress->postImport();

    }

}