<?php
/**
 * Created by Green Studio.
 * File: LoginController.class.php
 * User: TianShuo
 * Date: 14-1-25
 * Time: 上午10:39
 */

namespace Admin\Controller;

use Org\Net\IpLocation;
use Think\Controller;

class LoginController extends Controller
{
    public function _before_index()
    {

    }

    public function index()
    {
        $this->display();
    }

    public function login()
    {
        $ip = get_client_ip();
        echo $ip;
        $Ip = new IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件


        $area = $Ip->getlocation('203.34.5.66'); // 获取某个IP地址所在的位置IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件$area = $Ip->getlocation('203.34.5.66'); // 获取某个IP地址所在的位置

        $this->show(' ');
    }

}