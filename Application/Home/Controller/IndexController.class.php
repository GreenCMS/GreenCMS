<?php
/**
 * Created by Green Studio.
 * File: IndexController.class.php
 * User: TianShuo
 * Date: 14-1-11
 * Time: 下午1:40
 */
namespace Home\Controller;

use Home\Controller\HomeBaseController;


/**
 * Class IndexController
 * @package Home\Controller
 */
class IndexController extends HomeBaseController {

    /**
     *
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * 首页
     */
    public function index() {
        $this->display('index');
    }
}