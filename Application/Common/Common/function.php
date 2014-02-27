<?php
/**
 * Created by Green Studio.
 * File: function.php
 * User: TianShuo
 * Date: 14-1-14
 * Time: 下午11:09
 */

include APP_PATH . 'Common/Common/common_router.php';

const GREENCMS_ADDON_PATH = './Addons/';


function current_timestamp()
{
    $timestamp=date('Y-m-d H:i:s', time()-TIME_FIX);
    return $timestamp;
}

function object_to_array($obj)
{
    $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
    foreach ($_arr as $key => $val) {

        $val = (is_array($val) || is_object($val)) ? object_to_array($val) : $val;
        $arr[$key] = $val;
    }
    return $arr;
}

function get_url($url = '', $vars = '')
{
    $url_arr = preg_split('/\//', $url);

    if (sizeof($url_arr) == 2) {
        $url = 'Home/' . $url;

        $URL_MODEL_TEMP = C('URL_MODEL');
        C('URL_MODEL', (int)get_kv('home_url_model'));
        $url_return = U($url, $vars, $suffix = true, $domain = false);
        C('URL_MODEL', $URL_MODEL_TEMP);

        if ($URL_MODEL_TEMP == 2) $url_return = str_replace('/home', '', $url_return);
        if ($URL_MODEL_TEMP == 0) $url_return = str_replace('?m=home&', '/index.php?', $url_return);
        $url_return = str_replace('index.php/index.php', 'index.php', $url_return);

    } else {
        $url_return = U($url, $vars, $suffix = true, $domain = false);
    }
    return $url_return;

}

function encrypt($data)
{
    //return md5($data);
    return md5(C("AUTH_CODE") . md5($data));
}


/**
 * @param $res
 *
 * @function        打印数组
 */
function print_array(& $res)
{
    //dump($res);
    echo '<pre>';
    print_r($res);
    echo '</pre>';


}

/**
 * @param $i
 * 判断是否置顶
 */
function is_top($i)
{
    if ($i == 1) {
        echo '【固顶】';
    }
}

/**
 * @param $test判断是否为空
 */
function is_empty($test)
{
    if ($test == '') {
        echo '空';
    } else {
        echo $test;
    }
}


/**
 *
 */
function get_opinion($key, $db = false, $cache = true)
{
    if (!$db)
        return C($key);
    else {
        $res = D('Options')->cache($cache)->where(array('option_name' => $key))->find();
        return $res['option_value'];
    }

}

function get_kv($key, $cache = true, $default = '')
{
    if ($cache) {
        $kv_array = C('kv');
        if ($kv_array[$key] != '') return $kv_array[$key];
    }

    $options = D('Kv')->field('kv_value')->where(array('kv_key' => $key))->find();
    if ($options['kv_value'] == '')
        return $default;
    return $options['kv_value'];
}

function set_kv($key, $value)
{
    $data['kv_value'] = $value;
    if (exist_kv($key)) {
        $res = D('Kv')->where(array('kv_key' => $key))->data($data)->save();
    } else {
        $data['kv_key'] = $key;
        $res = D('Kv')->data($data)->add();
    }
    return $res;
}


function exist_kv($key)
{

    $options = D('Kv')->where(array('kv_key' => $key))->find();

    if ($options == null) {
        return false;
    } else {
        return true;
    }
}

/**
 * 数组降维
 */
function array2str($res)
{
    $str = '';
    foreach ($res as $each) {
        $str .= $each . ',';
    }
    return $str;
}


function array_sort($arr, $keys, $type = 'desc')
{
    $key_value = $new_array = array();
    foreach ($arr as $k => $v) {
        $key_value[$k] = $v[$keys];
    }
    if ($type == 'asc') {
        asort($key_value);
    } else {
        arsort($key_value);
    }
    reset($key_value);
    foreach ($key_value as $k => $v) {
        $new_array[$k] = $arr[$k];
    }
    return $new_array;
}


/**
 * @param $Timestamp
 * @param string $need
 *
 * @return mixed
 */
function getTimestamp($Timestamp, $need = 'timestamp')
{
    $array = explode("-", $Timestamp);
    $year = $array [0];
    $month = $array [1];

    $array = explode(":", $array [2]);
    $minute = $array [1];
    $second = $array [2];

    $array = explode(" ", $array [0]);
    $day = $array [0];
    $hour = $array [1];

    $timestamp = mktime($hour, $minute, $second, $month, $day, $year);

    if ($need === 'hour') {
        return $hour;
    } else if ($need === 'minute') {
        return $minute;
    } else if ($need === 'second') {
        return $second;
    } else if ($need === 'month') {
        return $month;
    } else if ($need === 'day') {
        return $day;
    } else if ($need === 'year') {
        return $year;
    } else {
        return date($need, $timestamp);
    }

}

function getTimeURL($Timestamp, $type = 'single')
{
    $array = explode("-", $Timestamp);
    $year = $array [0];
    $month = $array [1];

    $array = explode(":", $array [2]);
    $minute = $array [1];
    $second = $array [2];

    $array = explode(" ", $array [0]);
    $day = $array [0];
    $hour = $array [1];

    $url = '';
    $url .= '<a href="' . get_url('Archive/' . $type, array('year' => $year)) . '">' . $year . '</a>';
    $url .= '-<a href="' . get_url('Archive/' . $type, array('year' => $year, 'month' => $month)) . '">' . $month . '</a>';
    $url .= '-<a href="' . get_url('Archive/' . $type, array('year' => $year, 'month' => $month, 'day' => $day)) . '">' . $day . '</a>';

    return $url;

}

/**
 * 二位数组转化为一维数组
 * @param 二维数组
 *
 * @return array 一维数组
 */
function array_multi2single($array)
{

    static $result_array = array();
    foreach ($array as $value) {
        if (is_array($value)) {
            array_multi2single($value);
        } else
            $result_array [] = $value;
    }
    return $result_array;
}


/**
+----------------------------------------------------------
 * 功能：检测一个字符串是否是邮件地址格式
+----------------------------------------------------------
 * @param string $value    待检测字符串
+----------------------------------------------------------
 *
 * @return boolean
+----------------------------------------------------------
 */
function is_email($value)
{
    return preg_match("/^[0-9a-zA-Z]+(?:[\_\.\-][a-z0-9\-]+)*@[a-zA-Z0-9]+(?:[-.][a-zA-Z0-9]+)*\.[a-zA-Z]+$/i", $value);
}


/**
+----------------------------------------------------------
 * 功能：剔除危险的字符信息
+----------------------------------------------------------
 * @param string $val
+----------------------------------------------------------
 *
 * @return string 返回处理后的字符串
+----------------------------------------------------------
 */
function remove_xss($val)
{
    // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
    // this prevents some character re-spacing such as <java\0script>
    // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
    $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

    // straight replacements, the user should never need these since they're normal characters
    // this prevents like <IMG SRC=@avascript:alert('XSS')>
    $search = 'abcdefghijklmnopqrstuvwxyz';
    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $search .= '1234567890!@#$%^&*()';
    $search .= '~`";:?+/={}[]-_|\'\\';
    for ($i = 0; $i < strlen($search); $i++) {
        // ;? matches the ;, which is optional
        // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
        // @ @ search for the hex values
        $val = preg_replace('/(&#[xX]0{0,8}' . dechex(ord($search[$i])) . ';?)/i', $search[$i], $val); // with a ;
        // @ @ 0{0,7} matches '0' zero to seven times
        $val = preg_replace('/(&#0{0,8}' . ord($search[$i]) . ';?)/', $search[$i], $val); // with a ;
    }

    // now the only remaining whitespace attacks are \t, \n, and \r
    $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
    $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
    $ra = array_merge($ra1, $ra2);

    $found = true; // keep replacing as long as the previous round replaced something
    while ($found == true) {
        $val_before = $val;
        for ($i = 0; $i < sizeof($ra); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($ra[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                    $pattern .= '|';
                    $pattern .= '|(&#0{0,8}([9|10|13]);)';
                    $pattern .= ')*';
                }
                $pattern .= $ra[$i][$j];
            }
            $pattern .= '/i';
            $replacement = substr($ra[$i], 0, 2) . '<x>' . substr($ra[$i], 2); // add in <> to nerf the tag
            $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
            if ($val_before == $val) {
                // no replacements were made, so exit the loop
                $found = false;
            }
        }
    }
    return $val;
}

/**
 * 处理插件钩子
 * @param string $hook   钩子名称
 * @param mixed $params 传入参数
 * @return void
 */
function hook($hook, $params = array())
{
    \Think\Hook::listen($hook, $params);
}

/**
 * 获取插件类的类名
 * @param strng $name 插件名
 */
function get_addon_class($name)
{
    $class = "Addons\\{$name}\\{$name}Addon";
    return $class;
}

/**
 * 获取插件类的配置文件数组
 * @param string $name 插件名
 */
function get_addon_config($name)
{
    $class = get_addon_class($name);
    if (class_exists($class)) {
        $addon = new $class();
        return $addon->getConfig();
    } else {
        return array();
    }
}

/**
 * 插件显示内容里生成访问插件的url
 * @param string $url url
 * @param array $param 参数
 */
function addons_url($url, $param = array())
{
    $url = parse_url($url);
    $case = C('URL_CASE_INSENSITIVE');
    $addons = $case ? parse_name($url['scheme']) : $url['scheme'];
    $controller = $case ? parse_name($url['host']) : $url['host'];
    $action = trim($case ? strtolower($url['path']) : $url['path'], '/');

    /* 解析URL带的参数 */
    if (isset($url['query'])) {
        parse_str($url['query'], $query);
        $param = array_merge($query, $param);
    }

    /* 基础参数 */
    $params = array(
        '_addons'     => $addons,
        '_controller' => $controller,
        '_action'     => $action,
    );
    $params = array_merge($params, $param); //添加额外参数

    return U('Addons/execute', $params);
}

/**
 * 对查询结果集进行排序
 * @access public
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
 * @return array
 */
function list_sort_by($list, $field, $sortby = 'asc')
{
    if (is_array($list)) {
        $refer = $resultSet = array();
        foreach ($list as $i => $data)
            $refer[$i] = & $data[$field];
        switch ($sortby) {
            case 'asc': // 正向排序
                asort($refer);
                break;
            case 'desc': // 逆向排序
                arsort($refer);
                break;
            case 'nat': // 自然排序
                natcasesort($refer);
                break;
        }
        foreach ($refer as $key => $val)
            $resultSet[] = & $list[$key];
        return $resultSet;
    }
    return false;
}

/**
 * 字符串转换为数组，主要用于把分隔符调整到第二个参数
 * @param  string $str  要分割的字符串
 * @param  string $glue 分割符
 * @return array
 */
function str2arr($str, $glue = ',')
{
    return explode($glue, $str);
}

/**
 * 数组转换为字符串，主要用于把分隔符调整到第二个参数
 * @param  array $arr  要连接的数组
 * @param  string $glue 分割符
 * @return string
 */
function arr2str($arr, $glue = ',')
{
    return implode($glue, $arr);
}
