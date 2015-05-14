<?php
/**
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-10-23
 * Time: 下午10:35
 */

namespace Weixin\Util;


use Common\Util\Curl;

class SendMsg
{

    private $accessToken;

    private $msgUrl = 'https://api.weixin.qq.com/cgi-bin/message/custom/send';

    /**
     * 自动注入AccessToken
     */
    public function __construct()
    {
        $AccessToken = new AccessToken();
        $this->accessToken = $AccessToken->getAccessToken();

        $params['access_token'] = $this->accessToken;

        $this->msgUrl .= (strpos($this->msgUrl, '?') === false) ? '?' : '&';
        $this->msgUrl .= is_array($params) ? http_build_query($params) : $params;


    }


    /**
     * 文本
     * @param $tousername
     * @param $content 回复的消息内容（换行：在content中能够换行，微信客户端就支持换行显示）
     * @return string
     */
    public function text($tousername, $content)
    {

        $Curl = new Curl();


        //开始
        $template = array(
            'touser' => $tousername,
            'msgtype' => 'text',
            'text' => array(
                'content' => $content,
            ),
        );
        $template = json_encode($template);

        return $Curl->callApi($this->msgUrl, $template, 'POST');
    }

    /**
     * 图片
     * @param $tousername
     * @param $mediaId 通过上传多媒体文件，得到的id。
     * @return string
     */
    public function image($tousername, $mediaId)
    {

        $Curl = new Curl();

        $template = array(
            'touser' => $tousername,
            'msgtype' => 'image',
            'image' => array(
                'media_id' => $mediaId,
            ),
        );

        $template = json_encode($template);
        return $Curl->callApi($this->msgUrl, $template, 'POST');
    }

    /**
     * 语音
     * @param $tousername
     * @param $mediaId 通过上传多媒体文件，得到的id
     * @return string
     */
    public function voice($tousername, $mediaId)
    {

        $Curl = new Curl();

        $template = array(
            'touser' => $tousername,
            'msgtype' => 'voice',
            'voice' => array(
                'media_id' => $mediaId,
            ),
        );
        $template = json_encode($template);

        return $Curl->callApi($this->msgUrl, $template, 'POST');
    }

    /**
     * 视频
     * @param $tousername
     * @param $mediaId 通过上传多媒体文件，得到的id
     * @param $title 标题
     * @param $description 描述
     * @return string
     */
    public function video($tousername, $mediaId, $title, $description)
    {

        $Curl = new Curl();

        //开始
        $template = array(
            'touser' => $tousername,
            'msgtype' => 'video',
            'video' => array(
                'media_id' => $mediaId,
                'title' => $title,
                'description' => $description,
            ),
        );
        $template = json_encode($template);

        return $Curl->callApi($this->msgUrl, $template, 'POST');
    }

    /**
     * 音乐
     * @param $tousername
     * @param $title 标题
     * @param $description 描述
     * @param $musicUrl 音乐链接
     * @param $hqMusicUrl 高质量音乐链接，WIFI环境优先使用该链接播放音乐
     * @param $thumbMediaId 缩略图的媒体id，通过上传多媒体文件，得到的id
     * @return string
     */
    public function music($tousername, $title, $description, $musicUrl, $hqMusicUrl, $thumbMediaId)
    {

        $Curl = new Curl();

        //开始
        $template = array(
            'touser' => $tousername,
            'msgtype' => 'music',
            'music' => array(
                'title' => $title,
                'description' => $description,
                'musicurl' => $musicUrl,
                'hqmusicurl' => $hqMusicUrl,
                'thumb_media_id' => $thumbMediaId,
            ),
        );
        $template = json_encode($template);

        return $Curl->callApi($this->msgUrl, $template, 'POST');
    }

    /**
     * 图文消息 - 单个项目的准备工作，用于内嵌到self::news()中。现调用本方法，再调用self::news()
     *              多条图文消息信息，默认第一个item为大图,注意，如果调用本方法得到的数组总项数超过10，则将会无响应
     * @param $title 标题
     * @param $description 描述
     * @param $picUrl 图片链接，支持JPG、PNG格式，较好的效果为大图360*200，小图200*200
     * @param $url 点击图文消息跳转链接
     * @return string
     */
    public function newsItem($title, $description, $picUrl, $url)
    {
        return $template = array(
            'title' => $title,
            'description' => $description,
            'url' => $picUrl,
            'picurl' => $url,
        );
    }

    /**
     * 图文 - 先调用self::newsItem()再调用本方法
     * @param $tousername
     * @param $item 数组，每个项由self::newsItem()返回
     * @return string
     */
    public function news($tousername, $item)
    {

        $Curl = new Curl();

        //开始
        $template = array(
            'touser' => $tousername,
            'msgtype' => 'news',
            'news' => array(
                'articles' => $item
            ),
        );
        $template = json_encode($template);
        return $Curl->callApi($this->msgUrl, $template, 'POST');
    }


}