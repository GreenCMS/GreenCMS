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


    public function restore()
    {
        $data = '{
     "button":[
     {
                "name": "品牌介绍",
                "sub_button": [
                    {
                        "type": "click",
                        "name": "品牌介绍",
                        "key": "brand"
                    },
                    {
                        "type": "click",
                        "name": "产品列表",
                        "key": "category"
                    },
                    {
                        "type": "click",
                        "name": "努力时间轴",
                        "key": "timeline"
                    }
                                  ]
            },
            {
                "name": "最新活动",
                "sub_button": [
                    {
                        "type": "click",
                        "name": "优惠活动",
                        "key": "discount"
                    },
                    {
                        "type": "view",
                        "name": "用户测评",
                        "url": "http://www.easypot.cn/home/index.php/Home/Cat/detail/info/3.html"
                    },
                    {
                        "type": "click",
                        "name": "礼品方案",
                        "key": "gifts"
                    }
                ]
            },
            {
                "name": "我的园艺",
                "sub_button": [
                    {
                        "type": "click",
                        "name": "园艺心得",
                        "key": "gain"
                    },
                    {
                        "type": "view",
                        "name": "花友平台",
                        "url": "http://www.easypot.cn/home/index.php/Home/Cat/detail/info/4.html"
                    },
                    {
                        "type": "view",
                        "name": "售后反馈",
                        "url": "http://www.easypot.cn/home/index.php/Form/feedback"
                    },
                    {
                        "type": "view",
                        "name": "联系我们",
                        "url": "http://www.easypot.cn/home/index.php?m=Home&c=Post&a=page&info=4"
                    }
                ]
            }
        ]

}';

        return $this->create($data);
    }


    public function create($data)
    {

        if ($data != '') {
            $this->delete();
        }
        // $array = json_decode($data, true);

        // dump($array);
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
        return $tmpInfo;
    }

    public function delete()
    {
        $ACCESS_TOKEN = $this->getAccess();
        return file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=" . $ACCESS_TOKEN);
    }


    public function get()
    {
        $ACCESS_TOKEN = $this->getAccess();
        $menu_json = file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/get?access_token=" . $ACCESS_TOKEN);

        $menu_array = json_decode($menu_json, true);
        $menu_json = decodeUnicode(json_encode($menu_array['menu']));
        //  $menu = json_decode(trim($menu_json), true);
        //   $data['option_value'] = decodeUnicode(json_encode($menu['menu']));
        //    $res = D('Options')->where(array('option_name' => 'Weixin_menu'))->data($data)->save();

        return $menu_json;
    }

    public function save($menu_json)
    {
        $data['option_value'] = $menu_json;
        $res = D('Options')->where(array('option_name' => 'Weixin_menu'))->data($data)->save();

        return $res;
    }


}