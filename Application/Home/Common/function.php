<?php
/**
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-5-20
 * Time: 下午7:58
 */

/**
 * 装载主题自定义函数
 */
function load_theme_function()
{
    $THEME = I('get.theme', get_kv('home_theme', false, 'NovaGreenStudio'));

    $file_theme_function = WEB_ROOT . 'Application/Home/View/' . $THEME . '/function.php';
    if (file_exists($file_theme_function)) {
        include($file_theme_function);
    }
}

load_theme_function();