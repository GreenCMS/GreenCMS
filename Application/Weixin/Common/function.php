<?php
/**
 * Created by Green Studio.
 * File: function.php
 * User: TianShuo
 * Date: 14-2-18
 * Time: 下午1:58
 */

function getRealText($keyword)
{
    if ($keyword == 'text')
        return '文本';
    elseif ($keyword == 'click') {
        return '点击';
    } elseif ($keyword == 'news') {
        return '图文';
    } elseif ($keyword == 'text') {
        return '文本';
    } elseif ($keyword == 'image') {
        return '图片';
    }

}

function getMenuButtons()
{
    $menu = C('Weixin_menu');
    $menu = json_decode($menu, true);
    $array = $menu['button'];

    static $result_array = array();
    foreach ($array as $value) {
        if ($value['name'] != '' && $value['key'] != '') {
            $result_array [$value['key']] = $value['name'];
        }
        if (!empty($value['sub_button'])) {
            foreach ($value['sub_button'] as $value2) {
                if ($value2['name'] != '' && $value2['key'] != '') {
                    $result_array [$value2['key']] = $value2['name'];
                }
            }
        }
    }
    return $result_array;
}


function simple_post($url,$data){


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url );
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
