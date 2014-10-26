<?php
/**
 * Created by PhpStorm.
 * User: TianShuo
 * Date: 14-10-23
 * Time: 下午10:54
 */

namespace Weixin\Util;


use Common\Util\Curl;

class UserManagemant
{

    private $accessToken;

    private $groupsCreateURL = '';


    /**
     * 自动注入AccessToken
     */
    public function __construct()
    {
        $this->accessToken = new AccessToken();
        $this->accessToken = $this->accessToken->getAccessToken();
    }



    //-----------------------------组--------------管-------------理----------------------

    /**
     * 创建分组
     * @param $groupName 组名 UTF-8
     * @return JSON {"group": {"id": 107,"name": "test"}}
     */
    public function createGroup($groupName)
    {

        $Curl = new Curl();



        $queryUrl = 'https://api.weixin.qq.com/cgi-bin/groups/create?access_token=' . $this->accessToken;
        $data = '{"group":{"name":"' . $groupName . '"}}';
        return $Curl->callApi($queryUrl, $data, 'POST');

    }

    /**
     * 获取分组列表
     * @return JSON {"groups":[{"id": 0,"name": "未分组", "count": 72596}]}
     */
    public function getGroupList()
    {

        $Curl = new Curl();



        $queryUrl = 'https://api.weixin.qq.com/cgi-bin/groups/get?access_token=' . $this->accessToken;
        $data = '';
        return $Curl->callApi($queryUrl, $data, 'GET');

    }

    /**
     * 查询用户所在分组
     * @param $openId 用户唯一OPENID
     * @return JSON {"groupid": 102}
     */
    public function getGroupByOpenId($openId)
    {

        $Curl = new Curl();



        $queryUrl = 'https://api.weixin.qq.com/cgi-bin/groups/getid?access_token=' . $this->accessToken;
        $data = '{"openid":"' . $openId . '"}';
        return $Curl->callApi($queryUrl, $data, 'POST');

    }

    /**
     * 修改分组名
     * @param $groupId 要修改的分组ID
     * @param $groupName 新分组名
     * @return JSON {"errcode": 0, "errmsg": "ok"}
     */
    public function editGroupName($groupId, $groupName)
    {

        $Curl = new Curl();



        $queryUrl = 'https://api.weixin.qq.com/cgi-bin/groups/update?access_token=' . $this->accessToken;
        $data = '{"group":{"id":' . $groupId . ',"name":"' . $groupName . '"}}';
        return $Curl->callApi($queryUrl, $data, 'POST');

    }

    /**
     * 移动用户分组
     * @param $openid 要移动的用户OpenId
     * @param $to_groupid 移动到新的组ID
     * @return JSON {"errcode": 0, "errmsg": "ok"}
     */
    public function editUserGroup($openid, $to_groupid)
    {

        $Curl = new Curl();



        $queryUrl = 'https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=' . $this->accessToken;
        $data = '{"openid":"' . $openid . '","to_groupid":' . $to_groupid . '}';
        return $Curl->callApi($queryUrl, $data, 'POST');

    }

    //-----------------------------用-------户-------管--------理----------------------

    /**
     * 获取用户基本信息
     * @param $openId 用户唯一OpenId
     * @return JSON {
     * "subscribe": 1,
     * "openid": "o6_bmjrPTlm6_2sgVt7hMZOPfL2M",
     * "nickname": "Band",
     * "sex": 1,          //用户的性别，值为1时是男性，值为2时是女性，值为0时是未知
     * "language": "zh_CN",
     * "city": "广州",
     * "province": "广东",
     * "country": "中国",
     * "headimgurl":    "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/0",
     * "subscribe_time": 1382694957
     * }
     */
    public function getUserInfo($openId)
    {

        $Curl = new Curl();



        $queryUrl = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $this->accessToken . '&openid=' . $openId;
        $data = '';
        return $Curl->callApi($queryUrl, $data, 'GET');
    }

    /**
     * 获取关注者列表
     * @param string|\Weixin\Util\第一个拉取的OPENID $next_openid 第一个拉取的OPENID，不填默认从头开始拉取
     * @return JSON {"total":2,"count":2,"data":{"openid":["","OPENID1","OPENID2"]},"next_openid":"NEXT_OPENID"}
     */
    public function getFansList($next_openid = '')
    {

        $Curl = new Curl();



        if (empty($next_openid)) {
            $queryUrl = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token=' . $this->accessToken;
        } else {
            $queryUrl = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token=' . $this->accessToken . '&next_openid=' . $next_openid;
        }


        $data = '';
        return $Curl->callApi($queryUrl, $data, 'GET');
    }


    /**
     * 获取所有关注者列表
     */
    public function getAllFansList()
    {



    }




        /**
     * 获取网络状态
     * @return String network_type:wifi wifi网络。network_type:edge 非wifi,包含3G/2G。network_type:fail 网络断开连接
     */
    public function getNetworkState()
    {
        echo "WeixinJSBridge.invoke('getNetworkType',{},
		function(e){
	    	WeixinJSBridge.log(e.err_msg);
	    });";
    }


}