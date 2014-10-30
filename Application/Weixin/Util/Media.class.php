<?php
/**
 * Created by PhpStorm.
 * User: TianShuo
 * Date: 14-10-23
 * Time: 下午10:27
 */

namespace Weixin\Util;


use Common\Util\Curl;

class Media {

    private $accessToken;

    private $uploadMediaURL='http://file.api.weixin.qq.com/cgi-bin/media/upload';

    private $downloadMediaURL='http://file.api.weixin.qq.com/cgi-bin/media/get';


    /**
     * 自动注入AccessToken
     */
    public function __construct()
    {
        $AccessToken=new AccessToken();
        $this->accessToken=$AccessToken->getAccessToken();
    }


    /**
     * 多媒体上传。上传图片、语音、视频等文件到微信服务器，上传后服务器会返回对应的media_id，公众号此后可根据该media_id来获取多媒体。
     * 上传的多媒体文件有格式和大小限制，如下：
     * 图片（image）: 1M，支持JPG格式
     * 语音（voice）：2M，播放长度不超过60s，支持AMR\MP3格式
     * 视频（video）：10MB，支持MP4格式
     * 缩略图（thumb）：64KB，支持JPG格式
     * 媒体文件在后台保存时间为3天，即3天后media_id失效。
     *
     * @param $filename ，文件绝对路径
     * @param $type , 媒体文件类型，分别有图片（image）、语音（voice）、视频（video）和缩略图（thumb）
     * @return bool|mixed|string {"type":"TYPE","media_id":"MEDIA_ID","created_at":123456789}
     */
    public function upload($filename, $type){

        $Curl = new Curl();

        $params['access_token'] =$this->accessToken;
        $params['type'] = $type;

        $data['media'] =  '@' . $filename;

        $url=$this->uploadMediaURL;
        $url.= (strpos($url, '?') === false) ? '?' : '&';
        $url.= is_array($params) ? http_build_query($params) : $params;



        $res = $Curl->callApi($url, $data, 'POST',true,false);

        return $res;
    }

    /**
     * 下载多媒体文件
     * @param $mediaId 多媒体ID
     * @return 头信息如下
     *
     * HTTP/1.1 200 OK
     * Connection: close
     * Content-Type: image/jpeg
     * Content-disposition: attachment; filename="MEDIA_ID.jpg"
     * Date: Sun, 06 Jan 2013 10:20:18 GMT
     * Cache-Control: no-cache, must-revalidate
     * Content-Length: 339721
     * curl -G "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=ACCESS_TOKEN&media_id=MEDIA_ID"
     */
    public function download($mediaId){

        $Curl = new Curl();

        $params['access_token'] =$this->accessToken;
        $params['media_id'] = $mediaId;
        $res = $Curl->callApi($this->downloadMediaURL, $params, 'GET');

        return $res;
    }
 }