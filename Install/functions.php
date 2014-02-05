<?php

function RunMagicQuotes(&$str)
{
    if(!get_magic_quotes_gpc()) {
        if( is_array($str) )
            foreach($str as $key => $val) $str[$key] = RunMagicQuotes($val);
        else
            $str = addslashes($str);
    }
    return $str;
}

function gdversion()
{
  //没启用php.ini函数的情况下如果有GD默认视作2.0以上版本
  if(!function_exists('phpinfo'))
  {
      if(function_exists('imagecreate')) return '2.0';
      else return 0;
  }
  else
  {
    ob_start();
    phpinfo(8);
    $module_info = ob_get_contents();
    ob_end_clean();
    if(preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i", $module_info,$matches)) {   $gdversion_h = $matches[1];  }
    else {  $gdversion_h = 0; }
    return $gdversion_h;
  }
}



function TestWrite($d)
{
    $tfile = '_green.txt';
    $d = preg_replace("#\/$#", '', $d);
    $fp = @fopen($d.'/'.$tfile,'w');
    if(!$fp) return false;
    else
    {
        fclose($fp);
        $rs = @unlink($d.'/'.$tfile);
        if($rs) return true;
        else return false;
    }
}

/*
	获取远程文件内容
*/
function fopen_url($url)  
{  

	if (function_exists('file_get_contents')) 
	{  
		$file_content = @file_get_contents($url); 
	} 
	elseif (ini_get('allow_url_fopen') && ($file = @fopen($url, 'rb')))
	{  
		$i = 0;  
		while (!feof($file) && $i++ < 1000) 
		{  
			$file_content .= strtolower(fread($file, 4096));  
		}  
		fclose($file);  

	} 
	elseif (function_exists('curl_init')) 
	{  
		$curl_handle = curl_init();  
		curl_setopt($curl_handle, CURLOPT_URL, $url);  
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT,2);  
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER,1);  
		curl_setopt($curl_handle, CURLOPT_FAILONERROR,1);  
		curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Trackback Spam Check'); //引用垃圾邮件检查 
		$file_content = curl_exec($curl_handle);  
		curl_close($curl_handle);  
	}
	else 
	{  
		$file_content = '';  
	}  
	return $file_content;  
} 
	
function fopen_url_test()
{
	//$content =  fopen_url(str_replace('?'.$_SERVER["QUERY_STRING"],'',curPageURL()));
	$content =  fopen_url('http://blog.xjh1994.com');
	if(empty($content)) return false;
	return true;
}

function app_cache($cmspath)
{
	fopen_url($cmspath.'/index.php');
	fopen_url($cmspath.'/admin.php');
    delDir('../Data');
}

function delDir($dirName)
{
    if (!file_exists($dirName)) {
        return false;
    }

    $dir = opendir($dirName);
    while ($fileName = readdir($dir)) {
        $file = $dirName . '/' . $fileName;
        if ($fileName != '.' && $fileName != '..') {
            if (is_dir($file)) {
                delDir($file);
            } else {
                unlink($file);
            }
        }
    }
    closedir($dir);
    return rmdir($dirName);
}


function GetIP(){
	if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
		$ip = getenv("HTTP_CLIENT_IP");
	else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
		$ip = getenv("REMOTE_ADDR");
	else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
		$ip = $_SERVER['REMOTE_ADDR'];
	else
		$ip = "unknown";
	return($ip);
}



?>