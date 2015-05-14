<?php
/**
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-10-23
 * Time: 下午10:17
 */

namespace Weixin\Util;


use Common\Util\Curl;

class Menu
{

    private $accessToken;

    private $createMenuUrl = 'https://api.weixin.qq.com/cgi-bin/menu/create';

    private $getMenuUrl = 'https://api.weixin.qq.com/cgi-bin/menu/get';

    private $delMenuUrl = 'https://api.weixin.qq.com/cgi-bin/menu/delete';

    /**
     * 自动注入AccessToken
     */
    public function __construct()
    {
        $AccessToken = new AccessToken();
        $this->accessToken = $AccessToken->getAccessToken();

    }


    /**
     * 添加菜单，一级菜单最多3个，每个一级菜单最多可以有5个二级菜单
     * @param $menuList
     *          array(
     * array('id'=>'', 'pid'=>'', 'name'=>'', 'type'=>'', 'code'=>),
     * array('id'=>'', 'pid'=>'', 'name'=>'', 'type'=>'', 'code'=>),
     * array('id'=>'', 'pid'=>'', 'name'=>'', 'type'=>'', 'code'=>),
     *          );
     *          'type'是菜单类型，数字1或者2，1是view类型，2是click类型
     *          'code'是view类型的URL或者click类型的key
     *
     * @return bool
     */
    public function createMenu($menuList)
    {
        //树形排布
        $menuList2 = $menuList;
        foreach ($menuList as $key => $menu) {
            foreach ($menuList2 as $k => $menu2) {
                if ($menu['id'] == $menu2['pid']) {
                    $menuList[$key]['sub_button'][] = $menu2;
                    unset($menuList[$k]);
                }
            }
        }
        $typeView = 1;
        $typeClick = 2;
        //处理数据
        foreach ($menuList as $key => $menu) {
            //处理type和code
            if ($menu['type'] == $typeView) {
                $menuList[$key]['type'] = 'view';
                $menuList[$key]['url'] = $menu['code'];
                //处理URL。因为URL不能在转换JSON时被转为UNICODE
                $menuList[$key]['url'] = urlencode($menuList[$key]['url']);
            } else if ($menu['type'] == $typeClick) {
                $menuList[$key]['type'] = 'click';
                $menuList[$key]['key'] = $menu['code'];
            }
            unset($menuList[$key]['code']);
            //处理PID和ID
            unset($menuList[$key]['id']);
            unset($menuList[$key]['pid']);
            //处理名字。因为汉字不能在转换JSON时被转为UNICODE
            $menuList[$key]['name'] = urlencode($menu['name']);
            //处理子类菜单
            if (isset($menu['sub_button'])) {
                unset($menuList[$key]['type']);
                foreach ($menu['sub_button'] as $k => $son) {
                    //处理type和code
                    if ($son['type'] == $typeView) {
                        $menuList[$key]['sub_button'][$k]['type'] = 'view';
                        $menuList[$key]['sub_button'][$k]['url'] = $son['code'];
                        $menuList[$key]['sub_button'][$k]['url'] = urlencode($menuList[$key]['sub_button'][$k]['url']);
                    } else if ($son['type'] == $typeClick) {
                        $menuList[$key]['sub_button'][$k]['type'] = 'click';
                        $menuList[$key]['sub_button'][$k]['key'] = $son['code'];
                    }
                    unset($menuList[$key]['sub_button'][$k]['code']);
                    //处理PID和ID
                    unset($menuList[$key]['sub_button'][$k]['id']);
                    unset($menuList[$key]['sub_button'][$k]['pid']);
                    //处理名字。因为汉字不能在转换JSON时被转为UNICODE
                    $menuList[$key]['sub_button'][$k]['name'] = urlencode($son['name']);
                }
            }
        }
        //整理格式
        $data = array();
        $menuList = array_values($menuList);
        $data['button'] = $menuList;
        //转换成JSON
        $data = json_encode($data);
        $data = urldecode($data);

        //获取ACCESS_TOKEN
        $Curl = new Curl();
        $params['access_token'] = $this->accessToken;

        $res = $Curl->callApi($this->createMenuUrl, $params, 'POST');

        if ($res['errcode'] == 0) {
            return true;
        }
        return false;
    }


    /**
     * 获取当前菜单
     * @return bool|mixed|string
     */
    public function getMenu()
    {

        $Curl = new Curl();

        $params['access_token'] = $this->accessToken;
        $res = $Curl->callApi($this->getMenuUrl, $params, 'GET');

        return $res;
    }


    /**
     * 删除菜单
     * @return bool|mixed|string
     */
    public function delMenu()
    {

        $Curl = new Curl();

        $params['access_token'] = $this->accessToken;
        $res = $Curl->callApi($this->delMenuUrl, $params, 'GET');

        return $res;
    }


}