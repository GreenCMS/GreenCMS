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

class MenuEvent extends WeixinCoreController
{

    private $access = '';

    public function create()
    {
        $data = C('Weixin_menu');
        $ACCESS_TOKEN = $this->getAccess();


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $ACCESS_TOKEN);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }

        curl_close($ch);
        echo $tmpInfo;
    }

    public function delete()
    {
        $ACCESS_TOKEN = $this->getAccess();
        echo file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=" . $ACCESS_TOKEN);
    }


    public function get()
    {
        $ACCESS_TOKEN = $this->getAccess();
        echo file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/get?access_token=" . $ACCESS_TOKEN);
     }




}