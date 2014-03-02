<?php
/**
 * Created by Green Studio.
 * File: function.php
 * User: TianShuo
 * Date: 14-2-18
 * Time: 下午1:58
 */

function getRealText($keyword)
{
    if ($keyword == 'text')
        return '文本';
    elseif ($keyword == 'click') {
        return '点击';
    } elseif ($keyword == 'news') {
        return '图文';
    } elseif ($keyword == 'text') {
        return '文本';
    } elseif ($keyword == 'image') {
        return '图片';
    } elseif ($keyword == 0) {
        return '已';
    } elseif ($keyword == 1) {
        return '未';
    }

}

function getMenuButtons()
{
    $menu = C('Weixin_menu');
    $menu = json_decode($menu, true);
    $array = $menu['button'];

    static $result_array = array();
    foreach ($array as $value) {
        if ($value['name'] != '' && $value['key'] != '') {
            $result_array [$value['key']] = $value['name'];
        }
        if (!empty($value['sub_button'])) {
            foreach ($value['sub_button'] as $value2) {
                if ($value2['name'] != '' && $value2['key'] != '') {
                    $result_array [$value2['key']] = $value2['name'];
                }
            }
        }
    }
    return $result_array;
}


function simple_post($url,$data){


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url );
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $tmpInfo = curl_exec($ch);
    if (curl_errno($ch)) {
        return curl_error($ch);
    }

    curl_close($ch);
    return $tmpInfo;


}



/**
 *
 * @package 二维数组排序
 * @version $Id: FunctionsMain.inc.php,v 1.32 2005/09/24 11:38:37 wwccss Exp $
 *
 *
 *          Sort an two-dimension array by some level two items use
 *          array_multisort() function.
 *
 *
 *
 *          sysSortArray($Array,&quot;Key1&quot;,&quot;SORT_ASC&quot;,&quot;SORT_RETULAR&quot;,&quot;Key2&quot;……)
 * @param array $ArrayData
 *        	the array to sort.
 * @param string $KeyName1
 *        	the first item to sort by.
 * @param string $SortOrder1
 *        	the order to sort by(&quot;SORT_ASC&quot;|&quot;SORT_DESC&quot;)
 * @param string $SortType1
 *        	the sort
 *        	type(&quot;SORT_REGULAR&quot;|&quot;SORT_NUMERIC&quot;|&quot;SORT_STRING&quot;)
 * @return array sorted array.
 */
function sysSortArray($ArrayData, $KeyName1, $SortOrder1 = "SORT_ASC", $SortType1 = "SORT_REGULAR") {
    if (! is_array ( $ArrayData )) {
        return $ArrayData;
    }

    // Get args number.
    $ArgCount = func_num_args ();

    // Get keys to sort by and put them to SortRule array.
    for($I = 1; $I < $ArgCount; $I ++) {
        $Arg = func_get_arg ( $I );
        if (! eregi ( "SORT", $Arg )) {
            $KeyNameList [] = $Arg;
            $SortRule [] = '$' . $Arg;
        } else {
            $SortRule [] = $Arg;
        }
    }

    // Get the values according to the keys and put them to array.
    foreach ( $ArrayData as $Key => $Info ) {
        foreach ( $KeyNameList as $KeyName ) {
            ${
            $KeyName} [$Key] = $Info [$KeyName];
        }
    }

    // Create the eval string and eval it.
    $EvalString = 'array_multisort(' . join ( ",", $SortRule ) . ',$ArrayData);';
    eval ( $EvalString );
    return $ArrayData;
}
