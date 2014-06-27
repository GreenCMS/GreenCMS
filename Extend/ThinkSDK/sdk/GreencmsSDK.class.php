<?php
namespace Extend\ThinkSDK;
use \Think\Exception;
// +----------------------------------------------------------------------
// | GreenO
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.green.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Timothy Zhang <zts1993@gmail.com> <http://www.zts1993.com>
// +----------------------------------------------------------------------
// | GreencmsSDK.class.php 2013-02-25
// +----------------------------------------------------------------------

class GreencmsSDK extends ThinkOauth{
	/**
	 * 获取requestCode的api接口
	 * @var string
	 */
	protected $GetRequestCodeURL = 'http://localhost:8080/oauth2/authorize';

	/**
	 * 获取access_token的api接口
	 * @var string
	 */
	protected $GetAccessTokenURL = 'http://localhost:8080/oauth2/access_token';

	/**
	 * API根路径
	 * @var string
	 */
	protected $ApiBase = 'http://localhost:8080/2/';

    /**
     * 组装接口调用参数 并调用接口
     * @param  string $api GreenCMS API
     * @param  string $param 调用API的额外参数
     * @param  string $method HTTP请求方法 默认为GET
     * @param bool $multi
     * @return string json
     */
	public function call($api, $param = '', $method = 'GET', $multi = false){		
		/* GreenCMS调用公共参数 */
		$params = array(
			'access_token' => $this->Token['access_token'],
		);
		
		$vars = $this->param($params, $param);
		$data = $this->http($this->url($api), $vars, $method, array(), $multi);
		return json_decode($data, true);
	}

    /**
     * 解析access_token方法请求后的返回值
     * @param string $result 获取access_token的方法的返回值
     * @param $extend
     * @throws \Think\Exception
     * @return mixed
     */
	protected function parseToken($result, $extend){
		$data = json_decode($result, true);

        if($data['access_token'] && $data['expires_in'] && $data['remind_in'] && $data['uid']){
			$data['openid'] = $data['uid'];
			unset($data['uid']);
			return $data;
		} else
			throw new Exception("获取GreenOAuth ACCESS_TOKEN出错：{$data['error']}");
	}

    /**
     * 获取当前授权应用的openid
     * @throws \Think\Exception
     * @return string
     */
	public function openid(){
		$data = $this->Token;
		if(isset($data['openid']))
			return $data['openid'];
		else
			throw new Exception('没有获取到GreenOAuth 用户ID！');
	}
	
}