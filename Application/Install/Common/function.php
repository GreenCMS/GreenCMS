<?php
/**
 * Created by Green Studio.
 * File: functions.php
 * User: TianShuo
 * Date: 14-2-6
 * Time: 下午9:50
 */
function testDB($dbhost, $dbuser, $dbpwd)
{
    if (mysql_connect($dbhost, $dbuser, $dbpwd))
        return true;
    else
        return false;
}

function gdversion()
{
    //没启用php.ini函数的情况下如果有GD默认视作2.0以上版本
    if (!function_exists('phpinfo')) {
        if (function_exists('imagecreate')) return '2.0';
        else return 0;
    } else {
        ob_start();
        phpinfo(8);
        $module_info = ob_get_contents();
        ob_end_clean();
        if (preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i", $module_info, $matches)) {
            $gdversion_h = $matches[1];
        } else {
            $gdversion_h = 0;
        }
        return $gdversion_h;
    }
}


function TestWrite($d)
{
    $tfile = '_green.txt';
    $d = preg_replace("#\/$#", '', $d);
    $fp = @fopen($d . '/' . $tfile, 'w');
    if (!$fp) return false;
    else {
        fclose($fp);
        $rs = @unlink($d . '/' . $tfile);
        if ($rs) return true;
        else return false;
    }
}

//
//function delDir($dirName)
//{
//    if (!file_exists($dirName)) {
//        return false;
//    }
//
//    $dir = opendir($dirName);
//    while ($fileName = readdir($dir)) {
//        $file = $dirName . '/' . $fileName;
//        if ($fileName != '.' && $fileName != '..') {
//            if (is_dir($file)) {
//                delDir($file);
//            } else {
//                unlink($file);
//            }
//        }
//    }
//    closedir($dir);
//    return rmdir($dirName);
//}


//function GetIP()
//{
//    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
//        $ip = getenv("HTTP_CLIENT_IP");
//    else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
//        $ip = getenv("HTTP_X_FORWARDED_FOR");
//    else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
//        $ip = getenv("REMOTE_ADDR");
//    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
//        $ip = $_SERVER['REMOTE_ADDR'];
//    else
//        $ip = "unknown";
//    return ($ip);
//}


function insertDB($file, $conn)
{
    if ($file == '')
        die('文件不存在');
    $file = fopen($file, "r");
    $sql = "";
    while (!feof($file)) {
        $tem = trim(fgets($file));
        if (empty($tem) || $tem[0] == '#' || ($tem[0] == '-' && $tem[1] == '-'))
            continue;
        $end = (int)(strlen($tem) - 1);
        if ($tem[$end] == ";") {
            $sql .= $tem;
            // $sql = str_replace("`", "", $sql);
            mysql_query($sql, $conn);
            $sql = "";
        } else {
            $sql .= $tem;
        }
    }
    fclose($file);


}
