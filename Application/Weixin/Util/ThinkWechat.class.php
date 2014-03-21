<?php

namespace Weixin\Util;


class ThinkWechat
{

    /**
     * 微信推送过来的数据或响应数据
     * @var array
     * @author ZTS
     * @version 1.0
     */
    private $data = array();

    /**
     * 构造方法，用于实例化微信SDK
     * @param string $token 微信开放平台设置的TOKEN
     */
    public function __construct($token)
    {

        /**
         * warning 不检测签名加快速度
         */
        //$this->checkSignature($token) || exit;


        if (IS_GET) {
            exit($_GET['echostr']);
        } else {
            $xml = file_get_contents("php://input");
            \Think\Log::record('消息内容GET' . $xml);
            $xml = new \SimpleXMLElement($xml);
            $xml || exit;

            foreach ($xml as $key => $value) {

                $this->data[$key] = strval($value);
            }
        }
    }

    /**
     * 获取微信推送的数据
     * @return array 转换为数组后的数据
     */
    public function request()
    {
        return $this->data;
    }

    /**
     * * 响应微信发送的信息（自动回复）
     * @param  array $content 回复信息，文本信息为string类型
     * @param  string $type    消息类型
     * @param int|string $flag 是否新标刚接受到的信息
     * @internal param string $to 接收用户名
     * @internal param string $from 发送者用户名
     * @return string          XML字符串
     */
    public function response($content, $type = 'text', $flag = 0)
    {
        /* 基础数据 */
        $this->data = array(
            'ToUserName'   => $this->data['FromUserName'],
            'FromUserName' => $this->data['ToUserName'],
            'CreateTime'   => NOW_TIME,
            'MsgType'      => $type,
        );

        /* 添加类型数据 */
        $this->$type($content);

        /* 添加状态 */
        $this->data['FuncFlag'] = $flag;

        /* 转换数据为XML */
        $xml = new \SimpleXMLElement('<xml></xml>');
        $this->data2xml($xml, $this->data);

        \Think\Log::record('echo:' . $xml->asXML());

        exit($xml->asXML());
    }

    /**
     * 回复文本信息
     * @param  string $content 要回复的信息
     */
    private function text($content)
    {
        $this->data['Content'] = $content;
    }


    /**
     * 回复图片信息
     * @param  string $Image 要回复的MediaId
     */
    private function image($Image)
    {
        list(
            $Image['MediaId'],
            ) = $Image;
        $this->data['Image'] = $Image;
    }

    /**
     * 回复音乐信息
     * @param $music
     * @internal param string $content 要回复的音乐
     */
    private function music($music)
    {
        list(
            $music['Title'],
            $music['Description'],
            $music['MusicUrl'],
            $music['HQMusicUrl']
            ) = $music;
        $this->data['Music'] = $music;
    }

    /**
     * 回复图文信息
     * @param  string $news 要回复的图文内容
     */
    private function news($news)
    {
        $articles = array();
        foreach ($news as $key => $value) {
            list(
                $articles[$key]['Title'],
                $articles[$key]['Description'],
                $articles[$key]['PicUrl'],
                $articles[$key]['Url']
                ) = $value;
            if ($key >= 9) {
                break;
            } //最多只允许10调新闻
        }
        $this->data['ArticleCount'] = count($articles);
        $this->data['Articles'] = $articles;
    }

    /**
     * 数据XML编码
     * @param  object $xml  XML对象
     * @param  mixed $data 数据
     * @param  string $item 数字索引时的节点名称
     * @return string
     */
    private function data2xml($xml, $data, $item = 'item')
    {
        foreach ($data as $key => $value) {
            /* 指定默认的数字key */
            is_numeric($key) && $key = $item;

            /* 添加子元素 */
            if (is_array($value) || is_object($value)) {
                $child = $xml->addChild($key);
                $this->data2xml($child, $value, $item);
            } else {
                if (is_numeric($value)) {
                    $child = $xml->addChild($key, $value);
                } else {
                    $child = $xml->addChild($key);
                    $node = dom_import_simplexml($child);
                    $node->appendChild($node->ownerDocument->createCDATASection($value));
                }
            }
        }
    }

    /**
     * 对数据进行签名认证，确保是微信发送的数据
     * @param  string $token 微信开放平台设置的TOKEN
     * @return boolean       true-签名正确，false-签名错误
     */
    private function auth($token)
    {
        /* 获取数据 */
        $data = array($_GET['timestamp'], $_GET['nonce'], $token);
        $sign = $_GET['signature'];

        /* 对数据进行字典排序 */
        sort($data);

        /* 生成签名 */
        $signature = sha1(implode($data));

        return $signature === $sign;
    }


    /**
     * 新版签名检测算法
     * @param $token
     * @return bool
     */
    private function checkSignature($token)
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = $token;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

}
