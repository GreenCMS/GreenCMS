<?php
/**
 * Created by Green Studio.
 * File: MenuEvent.class.php
 * User: TianShuo
 * Date: 14-2-18
 * Time: 下午7:07
 */

namespace Weixin\Event;
use Weixin\Controller\WeixinCoreController;

class MenuEvent extends WeixinCoreController {

    private $access='';

    public function create()
    {
        $data =C('Weixin_menu');
        $ACCESS_TOKEN = $this->getAccess()['access_token'];
        echo createMenu($data, $ACCESS_TOKEN);
    }

    public function delete()
    {
        $ACCESS_TOKEN = $this->getAccess()['access_token'];
        echo deleteMenu($ACCESS_TOKEN);
    }


    public function get()
    {
        $ACCESS_TOKEN = $this->getAccess()['access_token'];
        echo getMenu($ACCESS_TOKEN);
    }

}