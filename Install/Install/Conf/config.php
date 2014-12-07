<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: config.php
 * User: Timothy Zhang
 * Date: 14-2-6
 * Time: 下午3:01
 */
return array(

    'DB_TYPE'              => GreenCMS_DB_TYPE,
    'DB_HOST'              => GreenCMS_DB_HOST,
    'DB_NAME'              => GreenCMS_DB_NAME,
    'DB_USER'              => GreenCMS_DB_USR,
    'DB_PWD'               => GreenCMS_DB_PWD,
    'DB_PORT'              => GreenCMS_DB_PORT,
    'DB_PREFIX'            => GreenCMS_DB_PREFIX, //测试是为空，生产环境需要自形添加 如 green_

    'AUTH_CODE'            => "ZTS", //安装完毕之后不要改变，否则所有密码都会出错

    'URL_MODEL' => 0,


);