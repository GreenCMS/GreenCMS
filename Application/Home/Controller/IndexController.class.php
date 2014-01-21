<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;

use Home\Controller\HomeBaseController;


class IndexController extends HomeBaseController
{

    function __construct()
    {
        parent::__construct();

    }

    public function index()
    {

        $this->display('index');
    }
}