<?php
/**
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-10-23
 * Time: 下午9:26
 */

namespace Common\Util;


class Curl
{

    private $_ch;
    private $_header;
    private $_body;

    private $_cookie = array();
    private $_options = array();
    private $_url = array();
    private $_referer = array();

    function __construct()
    {
        $this->_ch = curl_init();

        curl_setopt($this->_ch, CURLOPT_HEADER, true);
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, 1);//禁止直接显示获取的内容 重要
        curl_setopt($this->_ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书下同
        curl_setopt($this->_ch, CURLOPT_SSL_VERIFYHOST, false); //
    }

    public function setOption($optArray = array())
    {
        foreach ($optArray as $opt) {
            curl_setopt($this->_ch, $opt['key'], $opt['value']);
        }
    }


    public function fetch($queryUrl, $param = '', $method = 'get')
    {
        if (empty($queryUrl)) {
            return false;
        }
        $method = strtolower($method);
        $ret = '';
        $param = empty($param) ? array() : $param;
        if ($method == 'get') {
            $ret = $this->_httpGet($queryUrl, $param);
        } elseif ($method == 'post') {
            $ret = $this->_httpPost($queryUrl, $param, true);
        }
        if (!empty($ret)) {
            return $ret;
        }
        return false;
    }


    public function callApi($queryUrl, $param = '', $method = 'get', $is_json = true, $is_urlcode = true)
    {
        if (empty($queryUrl)) {
            return false;
        }
        $method = strtolower($method);
        $ret = '';
        $param = empty($param) ? array() : $param;
        if ($method == 'get') {
            $ret = $this->_httpGet($queryUrl, $param);
        } elseif ($method == 'post') {
            $ret = $this->_httpPost($queryUrl, $param, $is_urlcode);
        }
        if (!empty($ret)) {
            if ($is_json) {
                return json_decode($ret, true);
            } else {
                return $ret;
            }
        }
        return false;
    }


    private function _httpGet($url, $query = array())
    {

        if (!empty($query)) {
            $url .= (strpos($url, '?') === false) ? '?' : '&';
            $url .= is_array($query) ? http_build_query($query) : $query;
        }

        curl_setopt($this->_ch, CURLOPT_URL, $url);
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->_ch, CURLOPT_HEADER, 0);

        $ret = $this->_execute();

        return $ret;
    }

    private function _execute()
    {
        $response = curl_exec($this->_ch);

        $errno = curl_errno($this->_ch);

        if ($errno > 0) {
            throw new \Exception(curl_error($this->_ch), $errno);
        }

        return $response;
    }

    private function _httpPost($url, $query = array(), $is_urlcode = true)
    {
        if (is_array($query)) {
            foreach ($query as $key => $val) {
                if ($is_urlcode) {
                    $encode_key = urlencode($key);
                } else {
                    $encode_key = $key;
                }
                if ($encode_key != $key) {
                    unset($query[$key]);
                }
                if ($is_urlcode) {
                    $query[$encode_key] = urlencode($val);
                } else {
                    $query[$encode_key] = $val;
                }

            }
        }


        curl_setopt($this->_ch, CURLOPT_URL, $url);
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->_ch, CURLOPT_USERAGENT, 'Mozilla / 5.0 (compatible; MSIE 5.01; Windows NT 5.0)');

        curl_setopt($this->_ch, CURLOPT_HEADER, 0);
        curl_setopt($this->_ch, CURLOPT_POST, true);
        curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($this->_ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($this->_ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($this->_ch, CURLOPT_AUTOREFERER, 1);


        $ret = $this->_execute();


        return $ret;
    }


    function  __destruct()
    {

        if (is_resource($this->_ch)) {
            curl_close($this->_ch);
        }
    }

    private function _put($url, $query = array())
    {
        curl_setopt($this->_ch, CURLOPT_CUSTOMREQUEST, 'PUT');

        return $this->_httpPost($url, $query);
    }

    private function _delete($url, $query = array())
    {
        curl_setopt($this->_ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

        return $this->_httpPost($url, $query);
    }

    private function _head($url, $query = array())
    {
        curl_setopt($this->_ch, CURLOPT_CUSTOMREQUEST, 'HEAD');

        return $this->_httpPost($url, $query);
    }

}