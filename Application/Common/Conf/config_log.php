<?php

if (!defined('THINK_PATH'))
    exit();
$config_log = array(

    //日志
    //'LOG_RECORD' => true, // 开启日志记录
    'LOG_LEVEL' => 'SQL,DEBUG,EMERG,ALERT,CRIT,ERR,WARN,INFO,',

);
// 测试是为空，生产环境需要自形添加 如 green_
return $config_log;
		