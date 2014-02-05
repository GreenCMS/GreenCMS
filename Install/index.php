<?php
@set_time_limit(0);
//error_reporting(E_ALL || ~E_NOTICE ||~E_WARNING);
error_reporting(~E_NOTICE || ~E_WARNING);
require_once('functions.php');

$ip = GetIP();

file_get_contents("http://greenstudio.sinaapp.com/2/greenstudio/install/Counter.action"); //统计代码，安装后可删除

file_get_contents("http://cms.xjh1994.com/Green"); //统计代码，安装后可删除

$insLockfile = dirname(__FILE__) . '/install.lock';
$s_lang = 'utf-8';
$rnd_cookieEncode = chr(mt_rand(ord('A'), ord('Z'))) . chr(mt_rand(ord('a'), ord('z'))) . chr(mt_rand(ord('A'), ord('Z'))) . chr(mt_rand(ord('A'), ord('Z'))) . chr(mt_rand(ord('a'), ord('z'))) . mt_rand(1000, 9999) . chr(mt_rand(ord('A'), ord('Z')));
$dfDbname = 'greencms';

define('CMSROOT', preg_replace("#[\\\\\/]install#", '', dirname(__FILE__)));
define('CMSCONFIG', dirname(__FILE__) . '/../Public/Config/');
define('CMSBASIC', dirname(__FILE__) . '/../App/Lib/App/Conf');

header("Content-Type: text/html; charset={$s_lang}");

if (file_exists($insLockfile)) {
    echo '
        <html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        </head>
        <body>
     		   你已经安装过GreenCMS，如果想重新安装，请先删除站点install目录下的 install.lock 文件，然后再安装。
        </body>
        </html>';
    exit;
}

foreach (Array('_GET', '_POST', '_COOKIE') as $_request) {
    foreach ($$_request as $_k => $_v) ${$_k} = RunMagicQuotes($_v);
}

if (empty($step)) {
    $step = 1;
}
/*------------------------
使用协议书
function _1_Agreement()
------------------------*/
if ($step == 1) {
    include('./tpl/step-1.html');
    exit();
} /*------------------------
环境测试
function _2_TestEnv()
------------------------*/
else if ($step == 2) {
    $phpv = PHP_VERSION;
    $sp_os = PHP_OS;
    $sp_gd = gdversion();
    $sp_server = $_SERVER['SERVER_SOFTWARE'];
    $sp_host = (empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_HOST'] : $_SERVER['REMOTE_ADDR']);
    $sp_name = $_SERVER['SERVER_NAME'];
    $sp_max_execution_time = ini_get('max_execution_time');
    $sp_allow_reference = (ini_get('allow_call_time_pass_reference') ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');
    $sp_allow_url_fopen = (ini_get('allow_url_fopen') ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');
    $sp_safe_mode = (ini_get('safe_mode') ? '<font color=red>[×]On</font>' : '<font color=green>[√]Off</font>');
    $sp_gd = ($sp_gd > 0 ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');
    $sp_mysql = (function_exists('mysql_connect') ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');
    //$sp_PclZip = (true ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');
    //$sp_fopen_url = (fopen_url_test() ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');
    @unlink('data.zip');
    delDir('./_data');
    if ($sp_mysql == '<font color=red>[×]Off</font>')
        $sp_mysql_err = TRUE;
    else
        $sp_mysql_err = FALSE;
    $sp_testdirs = array(
        '/',
        '/Core/*',
        '/Public/*',
        '/Application/*',
        '/Data/*',
        '/Upload/*',
        '/Extend/*',
    );
    include('./tpl/step-2.html');
    exit();
} elseif ($step == 3) {
    if (!empty($_SERVER['REQUEST_URI']))
        $scriptName = $_SERVER['REQUEST_URI'];
    else
        $scriptName = $_SERVER['PHP_SELF'];

    $basepath = preg_replace("#\/install(.*)$#i", '', $scriptName);
    $cfg_cmspath = $basepath;

    if (!empty($_SERVER['HTTP_HOST'])) {
        $baseurl = 'http://' . $_SERVER['HTTP_HOST'] . $basepath;
        $cfg_basehost = 'http://' . $_SERVER['HTTP_HOST'];
    } else {
        $baseurl = "http://" . $_SERVER['SERVER_NAME'] . $basepath;
        $cfg_basehost = 'http://' . $_SERVER['SERVER_NAME'];
    }
    include('./tpl/step-3.html');
    exit();
} /*------------------------
普通安装
function _5_Setup()
------------------------*/
else if ($step == 5) {
    $adminuser = $_POST['adminuser'];
    $adminpwd = $_POST['adminuser'];
    $dbhost = $_POST['dbhost'];
    $dbuser = $_POST['dbuser'];
    $dbpwd = $_POST['dbpwd'];

    $conn = mysql_connect($dbhost, $dbuser, $dbpwd) or die("<script>alert('数据库服务器或登录密码无效，\\n\\n无法连接数据库，请重新设定！');history.go(-1);</script>");

    mysql_query("CREATE DATABASE IF NOT EXISTS `" . $dbname . "`;", $conn);

    mysql_select_db($dbname) or die("<script>alert('选择数据库失败，可能是你没权限，请预先创建一个数据库！');history.go(-1);</script>");

    //获得数据库版本信息
    $rs = mysql_query("SELECT VERSION();", $conn);
    $row = mysql_fetch_array($rs);
    $mysqlVersions = explode('.', trim($row[0]));
    $mysqlVersion = $mysqlVersions[0] . "." . $mysqlVersions[1];
    mysql_query("SET NAMES '$dblang',character_set_client=binary,sql_mode='';", $conn);

    $fp = fopen(dirname(__FILE__) . "/config.ini.php", "r");
    $configStr1 = fread($fp, filesize(dirname(__FILE__) . "/config.ini.php"));
    fclose($fp);

    //config.ini.php
    $configStr1 = str_replace("~dbhost~", $dbhost, $configStr1);
    $configStr1 = str_replace("~dbname~", $dbname, $configStr1);
    $configStr1 = str_replace("~dbuser~", $dbuser, $configStr1);
    $configStr1 = str_replace("~dbpwd~", $dbpwd, $configStr1);
    $configStr1 = str_replace("~dbprefix~", $dbprefix, $configStr1);
    $configStr1 = str_replace("~dbcookie~", $cookieencode, $configStr1);
    $configStr1 = str_replace("~dblang~", $dblang, $configStr1);

    @chmod(CMSCONFIG, 0777);
    $fp = fopen(CMSCONFIG . "config.ini.php", "w") or die("<script>alert('写入配置失败，请检查../Public/Config目录是否可写入！');history.go(-1);</script>");
    fwrite($fp, $configStr1);
    fclose($fp);

    if ($mysqlVersion >= 4.1) {
        $sql4tmp = "MyISAM DEFAULT CHARSET=" . $dblang;
    }

    //创建数据表&&导入数据

    $query = '';
    $fp = fopen(dirname(__FILE__) . '/sql/greencms.sql', 'r');
    while (!feof($fp)) {
        $line = rtrim(fgets($fp, 1024));
        if (preg_match("#;$#", $line)) {
            $query .= $line . "\n";
            $query = str_replace('#@__', $dbprefix, $query);
            if ($mysqlVersion < 4.1) {
                $rs = mysql_query($query, $conn);
            } else {
                if (preg_match('#CREATE#i', $query)) {
                    $rs = mysql_query(preg_replace("#MyISAM#i", $sql4tmp, $query), $conn);
                } else {
                    $rs = mysql_query($query, $conn);
                }
            }
            $query = '';
        } else if (!preg_match("#^(\/\/|--)#", $line)) {
            $query .= $line;
        }
    }
    fclose($fp);

    //更新配置
    $cquery = "Update `{$dbprefix}options` set value='{$cfg_webname}' where option_name='title';";
    mysql_query($cquery, $conn);
    $cquery = "Update `{$dbprefix}options` set value='{$cfg_basehost}' where option_name='site_url';";
    mysql_query($cquery, $conn);
    $cquery = "Update `{$dbprefix}options` set value='{$cfg_cmspath}' where option_name='cmspath';";
    mysql_query($cquery, $conn);

    //增加管理员帐号


    $time = date("Y-m-d H:m:s");
    $adminQuery = "INSERT INTO `{$dbprefix}user` (`user_id`, `user_login`, `user_pass`, `user_nicename`, `user_email`, `user_url`, `user_registered`, `user_activation_key`, `user_status`, `display_name`, `user_intro`, 
        `user_level`, `sessioncode`) VALUES(1, '{$adminuser}', '" . md5($adminpwd) . "', '管理员', 'admin@admin.com', '', '{$time}', '', 1, 'admin', '我是admin，欢迎使用', 2, '9ebd064cd9c62186872f1f23480474f3');";
    mysql_query($adminQuery, $conn);
    //安全密码设置
    $safeadminpwd = empty($safeadminpwd) ? xmd5($adminpwd) : xmd5($safeadminpwd);
    File::write_file('Admin/Common/safeauth.php', "<?php\treturn '{$safeadminpwd}';?>");

    /* //安装体验数据
    if($installdemo == 1)
    {
        $query = '';
        $fp = fopen(dirname(__FILE__).'/testData.sql','r');
        while(!feof($fp))
        {
            $line = rtrim(fgets($fp, 1024));
            if(preg_match("#;$#", $line))
            {
                $query .= $line;
                $query = str_replace('#@__',$dbprefix,$query);
                if($mysqlVersion < 4.1) $rs = mysql_query($query,$conn);
                else $rs = mysql_query(str_replace('#~lang~#',$dblang,$query),$conn);
                $query='';
            } else if(!preg_match("#^(\/\/|--)#", $line))
            {
                $query .= $line;
            }
        }
        fclose($fp);
    }
    else
    {
    
    } */

    //锁定安装程序
    $fp = fopen($insLockfile, 'w');
    fwrite($fp, 'ok');
    fclose($fp);
    //预先加载应用,并删除缓存
    app_cache($cfg_cmspath);
    include('./tpl/step-5.html');
    exit();
} /*------------------------
检测数据库是否有效
function _10_TestDbPwd()
------------------------*/
else if ($step == 10) {
    header("Pragma:no-cache\r\n");
    header("Cache-Control:no-cache\r\n");
    header("Expires:0\r\n");
    $conn = @mysql_connect($dbhost, $dbuser, $dbpwd);
    if ($conn) {
        if (empty($dbname)) {
            echo "<font color='green'>信息正确</font>";
        } else {
            $info = mysql_select_db($dbname, $conn) ? "<font color='red'>数据库已经存在，系统将覆盖数据库</font>" : "<font color='green'>数据库不存在,系统将自动创建</font>";
            echo $info;
        }
    } else {
        echo "<font color='red'>数据库连接失败！</font>";
    }
    @mysql_close($conn);
    exit();
}

function xmd5($str, $pattern = 'greencms', $num = 1)
{
    $ref = md5(strrev($str . $pattern));
    for ($i = 1; $i <= $num; $i++) {
        $ref = md5(strrev($ref . $pattern));
    }
    return $ref;
}