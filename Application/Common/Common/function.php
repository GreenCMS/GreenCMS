<?php
/**
 * Created by Green Studio.
 * File: function.php
 * User: TianShuo
 * Date: 14-1-14
 * Time: 下午11:09
 */

include APP_PATH . 'Common/Common/common_router.php';


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
    dump($res);
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
function get_opinion($key)
{
    return C($key);
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


/**
 * @param $Timestamp
 * @param string $need
 *
 * @return mixed
 */
function getTimestamp($Timestamp, $need = '$timestamp')
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
    }

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


