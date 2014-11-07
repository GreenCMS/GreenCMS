<?php
/**
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-6-1
 * Time: 下午9:58
 */

return array(


    'THINK_SDK_Sina' => array(
        'APP_KEY' => get_opinion('Oauth_Sina_APP_KEY'), //应用注册成功后分配的 APP ID
        'APP_SECRET' => get_opinion('Oauth_Sina_APP_SECRET'), //应用注册成功后分配的KEY
        'CALLBACK' => get_opinion('Oauth_Sina_CALLBACK'), //注册应用填写的callback
    ),
//
//    'THINK_SDK_Greencms' => array(
//        'APP_KEY' => '10086', //应用注册成功后分配的 APP ID
//        'APP_SECRET' => 'GreenCMS!', //应用注册成功后分配的KEY
//        'CALLBACK' => 'http://127.0.0.1/Green2014/index.php?s=/Oauth/Sns/callback/type/greencms',//注册应用填写的callback
//    ),


    'THINK_SDK_Greencms' => array(
        'APP_KEY' => get_opinion('Oauth_GreenCMS_APP_KEY'), //应用注册成功后分配的 APP ID
        'APP_SECRET' => get_opinion('Oauth_GreenCMS_APP_SECRET'), //应用注册成功后分配的KEY
        'CALLBACK' => get_opinion('Oauth_GreenCMS_CALLBACK'), //注册应用填写的callback
    ),


);