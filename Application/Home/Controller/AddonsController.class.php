<?php
namespace Home\Controller;

use Think\Controller;
use Home\Controller\HomeBaseController;

/**
 * 扩展控制器
 * 用于调度各个扩展的URL访问需求
 */
class AddonsController extends HomeBaseController
{

    function __construct()
    {
        parent::__construct();
    }

    protected $addons = null;

    /**
     * @param null $_addons
     * @param null $_controller
     * @param null $_action
     */
    public function execute($_addons = null, $_controller = null, $_action = null)
    {
        if (get_opinion('URL_CASE_INSENSITIVE')) {
            $_addons = ucfirst(parse_name($_addons, 1));
            $_controller = parse_name($_controller, 1);
        }

        if (!empty($_addons) && !empty($_controller) && !empty($_action)) {


            $Addons = A("Addons://{$_addons}/{$_controller}")->$_action();

        } else {
            $this->error('没有指定插件名称，控制器或操作！');
        }
    }

}
