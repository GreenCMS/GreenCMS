<?php

if (!defined('THINK_PATH'))
	exit();

$config_router=array(

		//URL模式
		'URL_MODEL'=>2,
		
		//开启路由!!建议url模型选择2，否则的话建议使用native模式
		'URL_ROUTER_ON'=>true,
		//路由定义
		'URL_ROUTE_RULES'=> array(
				'Single/:year/:month/:day/:info'=>'Single/detail', //年月日规则路由
				'Single/:year/:month/:info'=>'Single/detail', //年月规则路由
				'Single/:year/:info'=>'Single/detail', //年规则路由
				'Single/:info'=>'Single/detail', //普通规则路由

				'Page/:year/:month/:day/:info'=>'Page/detail', //年月日规则路由
				'Page/:year/:month/:info'=>'Page/detail', //年月规则路由
				'Page/:year/:info'=>'Page/detail', //年规则路由
				'Page/:info'=>'Page/detail', //普通规则路由

				'Tag/:info'=>'Tag/detail', //普通规则路由

				'Cat/:father1/:father2/:info'=>'Cat/detail', //普通规则路由
				'Cat/:father/:info'=>'Cat/detail', //普通规则路由
				'Cat/:info'=>'Cat/detail', //普通规则路由jius
		),
		/*
		 * 注意！顺序不能乱 只有不符合第一个条件才回去匹配第二个
*/

		'OUR_URL_MODEL' => "native",  //Single单页路由模式

		/**
		 * @var
			* native,
			* post_id,
			* post_name,
			* year/month/day/post_name,
			* year/month/day/post_id,
			* year/month/post_name,
			* year/month/post_id,
			* year/post_name,
			* year/post_id,
			* */

		'OUR_TAG_MODEL' => "native",  //TAG标签路由模式.
		/**
		 * @var
			* native
			* ID
			* slug
			*/

		'OUR_CAT_MODEL' => "native",  //TAG标签路由模式.
		/**
		 * @var
			* native
			* ID
			* slug
			*/



);

return $config_router;
