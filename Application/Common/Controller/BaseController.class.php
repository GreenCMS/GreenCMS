<?php
/**
 * Created by Green Studio.
 * File: BaseController.class.php
 * User: TianShuo
 * Date: 14-1-11
 * Time: 下午1:44
 */
namespace Common\Controller;

use Think\Controller;

/**
 * Class BaseController
 * @package Common\Controller
 */
class BaseController extends Controller {

    /**
     * check_verify
     */
    function check_verify() {
        if ( !APP_DEBUG ) {
            if ( $_SESSION['verify'] != md5( $_POST ['verify'] ) ) {
                $this->error( '验证码错误！' );
            }
        }

    }
}