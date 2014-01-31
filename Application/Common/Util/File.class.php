<?php
/**
 * Created by Green Studio.
 * File: File.class.php
 * User: TianShuo
 * Date: 14-1-31
 * Time: 下午2:53
 */

namespace Common\Util;


class File
{


    public static function byteFormat($bytes)
    {
        $size_text = array(" B", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
        return round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), 2) . $size_text[$i];
    }


    static public function readFile($filename)
    {
        $content = '';
        if (function_exists('file_get_contents')) {
            @$content = file_get_contents($filename);
        } else {
            if (@$fp = fopen($filename, 'r')) {
                @$content = fread($fp, filesize($filename));
                @fclose($fp);
            }
        }
        return $content;
    }


    public static function writeFile($filename, $writetext, $openmod = 'w')
    {
        if (@$fp = fopen($filename, $openmod)) {
            flock($fp, 2);
            fwrite($fp, $writetext);
            fclose($fp);
            return true;
        } else {
            return false;
        }
    }


    public static function delFile($file)
    {
        if (file_exists($file)) {
            return unlink($file);
        } else {
            return FALSE;
        }
    }


    public static function delAll($path, $delDir = FALSE)
    {
        $handle = opendir($path);
        if ($handle) {
            while (false !== ($item = readdir($handle))) {
                if ($item != "." && $item != "..")
                    is_dir("$path/$item") ? self::delAll("$path/$item", $delDir) : unlink("$path/$item");
            }
            closedir($handle);
            if ($delDir)
                return rmdir($path);
        } else {
            if (file_exists($path)) {
                return unlink($path);
            } else {
                return FALSE;
            }
        }
    }


    public static function delDir($dirName)
    {
        if (!file_exists($dirName)) {
            return false;
        }

        $dir = opendir($dirName);
        while ($fileName = readdir($dir)) {
            $file = $dirName . '/' . $fileName;
            if ($fileName != '.' && $fileName != '..') {
                if (is_dir($file)) {
                    self::delDir($file);
                } else {
                    unlink($file);
                }
            }
        }
        closedir($dir);
        return rmdir($dirName);
    }


    public static function copyDir($surDir, $toDir)
    {
        $surDir = rtrim($surDir, '/') . '/';
        $toDir = rtrim($toDir, '/') . '/';
        if (!file_exists($surDir)) {
            return false;
        }

        if (!file_exists($toDir)) {
            self::mkDir($toDir);
        }
        $file = opendir($surDir);
        while ($fileName = readdir($file)) {
            $file1 = $surDir . '/' . $fileName;
            $file2 = $toDir . '/' . $fileName;
            if ($fileName != '.' && $fileName != '..') {
                if (is_dir($file1)) {
                    self::copyDir($file1, $file2);
                } else {
                    copy($file1, $file2);
                }
            }
        }
        closedir($file);
        return true;
    }

    public static function mkDir($dir)
    {
        $dir = rtrim($dir, '/') . '/';
        if (!is_dir($dir)) {
            if (mkdir($dir, 0700) == false) {
                return false;
            }
            return true;
        }
        return true;
    }


    static function getDirs($dir)
    {
        $dir = rtrim($dir, '/') . '/';
        $dirArray [][] = NULL;
        if (false != ($handle = opendir($dir))) {
            $i = 0;
            $j = 0;
            while (false !== ($file = readdir($handle))) {
                if (is_dir($dir . $file)) { //判断是否文件夹
                    $dirArray ['dir'] [$i] = $file;
                    $i++;
                } else {
                    $dirArray ['file'] [$j] = $file;
                    $j++;
                }
            }
            closedir($handle);
        }
        return $dirArray;
    }


    public static function dirSize($dir)
    {
        if (self::readable($dir)) {
            $dir_list = opendir($dir);
            $dir_size = 0;
            while (false !== ($folder_or_file = readdir($dir_list))) {
                if ($folder_or_file != "." && $folder_or_file != "..") {
                    if (is_dir("$dir/$folder_or_file")) {
                        $dir_size += self::dirSize("$dir/$folder_or_file");
                    } else {
                        $dir_size += filesize("$dir/$folder_or_file");
                    }
                }
            }
            closedir($dir_list);
            return $dir_size;
        } else {
            return "不存在";
        }
    }

    public static function realSize($dir = null)
    {
        if (self::readable($dir)) {
            if (is_file($dir)) { // 对文件的判断
                return self::byteFormat(filesize($dir));
            } else
                return self::byteFormat(self::dirSize($dir));

        } else
            return "文件不存在";

    }


    public static function readable($dir = null)
    {
        if (($frst = file_get_contents($dir)) && is_file($dir)) {
            return true; // 是文件，并且可读
        } else { // 是目录
            if (is_dir($dir) && scandir($dir)) {
                return true; // 目录可读
            } else {
                return false;
            }
        }
    }

    public static function writeable($dir = null)
    {
        if (is_file($dir)) { // 对文件的判断
            return is_writeable($dir);
        } elseif (is_dir($dir)) {
            // 开始写入测试;
            $file = '_______' . time() . rand() . '_______';
            $file = $dir . '/' . $file;
            if (file_put_contents($file, '//')) {
                unlink($file); // 删除测试文件
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        };
    }

    public static function emptyDir($dir)
    {
        if (($files = @scandir($dir)) && count($files) <= 2)
            return true;
        return false;

    }

    public static function makeDir($path,$property=0777 )
    {
        return is_dir($path) or (self::makeDir(dirname($path),$property) and @mkdir($path, $property));
    }


    /**
     * 功能：生成zip压缩文件，存放都 WEB_CACHE_PATH 中
     * @param $files        array   需要压缩的文件
     * @param $filename     string  压缩后的zip文件名  包括zip后缀
     * @param $path         string  文件所在目录
     * @param $outDir       string  输出目录
     * @return array
     */
    public function zip($files, $filename, $outDir = WEB_CACHE_PATH, $path = DB_Backup_PATH)
    {
        $zip = new \ZipArchive;

        File::makeDir($outDir);

        $res = $zip->open($outDir . "\\" . $filename, \ZipArchive::CREATE);

        if ($res == TRUE) {
            foreach ($files as $file) {
                if ($t = $zip->addFile($path . $file, str_replace('/', '', $file))) {
                    $t = $zip->addFile($path . $file, str_replace('/', '', $file));
                }
            }
            $zip->close();
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 功能：解压缩zip文件，存放都 DB_Backup_PATH 中
     * @param $file         string   需要压缩的文件
     * @param $outDir       string   解压文件存放目录
     * @return array
     */
    function unzip($file, $outDir = DB_Backup_PATH)
    {
        $zip = new \ZipArchive();
        if ($zip->open(DB_Backup_PATH . "Zip/" . $file) !== TRUE)
            return FALSE;
        $zip->extractTo($outDir);
        $zip->close();
        return TRUE;
    }



}