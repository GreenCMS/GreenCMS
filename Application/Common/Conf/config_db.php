<?php

/* */
$config_db = array(

    /**

    'DB_FIELDS_CACHE' => true,
    'DB_SQL_BUILD_CACHE' => true,
     */

    // 数据库配置
    'DB_TYPE' => GreenCMS_DB_TYPE,
    'DB_HOST' => GreenCMS_DB_HOST,
    'DB_NAME' => GreenCMS_DB_NAME,
    'DB_USER' => GreenCMS_DB_USR,
    'DB_PWD' => GreenCMS_DB_PWD,
    'DB_PORT' => GreenCMS_DB_PORT,
    'DB_PREFIX' => GreenCMS_DB_PREFIX //测试是为空，生产环境需要自形添加 如 green_

);


return $config_db;
		
