<?php
/**
 * Created by Green Studio.
 * File: HomeBaseController.class.php
 * User: TianShuo
 * Date: 14-1-11
 * Time: 下午1:48
 */

namespace Home\Controller;
use Common\Controller\BaseController;

/**
 * Class HomeBaseController
 * @package Home\Controller
 */
class HomeBaseController extends BaseController {

    /**
     * 构造
     */
    function __construct() {
        parent::__construct();

        $this->newPosts = D ( 'Posts','Logic' )->getList ( 'single', 'post_date desc', 5, false );
        $this->friendurl = D ( 'Links','Logic' )->getList ( '5' );
        $this->custom_config();
    }

    /**
     * 获取配置
     * @return mixed
     */
    function get__config() {
        $options = D( 'Options' )->where( array( 'autoload' => 'yes' ) )->select();
        return $options;
    }

    /**
     * 用户存放在数据库中的配置，覆盖config中的
     */
    function custom_config() {
        $options = $this->get__config();
        foreach ($options as $config) {
            C($config['option_name'], $config['option_value']);
        }
    }

}