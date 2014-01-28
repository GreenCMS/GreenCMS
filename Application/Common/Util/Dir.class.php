<?php
/**
 * Created by Green Studio.
 * File: Dir.class.php
 * User: TianShuo
 * Date: 14-1-26
 * Time: 下午11:02
 */

namespace Common\Util;


class Dir
{
    private $_dir;

    /**
     * 目录类
     *
     * @author ZTS
     * @param string $dir
     *            目录
     */
    function __construct($dir)
    {
        $this->_dir = $dir;
    }

    function fommated_filesize($bytes, $decimals = 2)
    {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz [$factor];
    }

    /**
     * 计算目录大小
     *
     * @param string $dir
     *            目录
     * @return number 字节
     */
    public function dirSize($dir = null)
    {
        if ($dir == null) {
            $dir = $this->_dir;
        }
        if (!is_string($dir)) {
            throw new Exception ('目录名必须为string类型！');
        }
        $size = 0;
        $items = scandir($dir);
        foreach ($items as $item) {
            if (is_file($dir . '/' . $item)) {
                $size = $size + filesize($dir . '/' . $item);
            } elseif (is_dir($dir . '/' . $item) && '.' != $item && '..' != $item) {
                $size = $size + $this->dirSize($dir . '/' . $item);
            }
        }

        return ($size);
    }

    public function size($dir = null)
    {
        return $this->fommated_filesize($this->dirSize($dir));
    }

    public function realsize($dir = null)
    {
        if ($this->readable($dir)) {
            return $this->fommated_filesize(filesize($dir));
        } else {
            return "文件不存在";
        }
    }

    /**
     * +-----------------------------------------------------------------------------------------
     * 删除目录及目录下所有文件或删除指定文件
     * +-----------------------------------------------------------------------------------------
     *
     * @param str $path
     *            待删除目录路径
     * @param int $delDir
     *            是否删除目录，1或true删除目录，0或false则只删除文件保留目录（包含子目录）
     *            +-----------------------------------------------------------------------------------------
     * @return bool 返回删除状态
     *         +-----------------------------------------------------------------------------------------
     */
    public function delDirAndFile($path, $delDir = FALSE)
    {
        $handle = opendir($path);
        if ($handle) {
            while (false !== ($item = readdir($handle))) {
                if ($item != "." && $item != "..")
                    is_dir("$path/$item") ? $this->delDirAndFile("$path/$item", $delDir) : unlink("$path/$item");
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

    /**
     * 判断文件或目录可读
     *
     * @author 李俊
     * @param string $dir
     *            目录名
     * @return bool
     */
    function readable($dir = null)
    {
        if ($dir == null) {
            $dir = $this->_dir;
        }
        if (!is_string($dir)) {
            throw new Exception ('目录名必须为string类型！');
        }
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

    /**
     * 判断文件或目录是否可写
     *
     * @author 李俊
     * @param string $dir
     *            目录名
     * @return bool
     */
    function writeable($dir = null)
    {
        if ($dir == null) {
            $dir = $this->_dir;
        }
        if (!is_string($dir)) {
            throw new Exception ('目录名必须为string类型！');
        }
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

    /**
     * 文件或目录权限检查函数
     *
     * @access public
     * @param string $file_path
     *            文件路径
     * @param bool $rename_prv
     *            是否在检查修改权限时检查执行rename()函数的权限
     *
     * @return <b style='color:blue;'>int</b> <b style='color:red;'>15具有所有权限，0拒绝访问，1拒绝写入</b><br>返回值的取值范围为{0 <= x <= 15}，每个值表示的含义可由四位二进制数组合推出。
     *         返回值在二进制计数法中，四位由高到低分别代表
     *         可执行rename()函数权限、可对文件追加内容权限、可写入文件权限、可读取文件权限。
     */
    function file_mode_info($file_path)
    {
        /* 如果不存在，则不可读、不可写、不可改 */
        if (!file_exists($file_path)) {
            return false;
        }
        $mark = 0;
        if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
            /* 测试文件 */
            $test_file = $file_path . '/cf_test.txt';
            /* 如果是目录 */
            if (is_dir($file_path)) {
                /* 检查目录是否可读 */
                $dir = @opendir($file_path);
                if ($dir === false) {
                    return $mark; // 如果目录打开失败，直接返回目录不可修改、不可写、不可读
                }
                if (@readdir($dir) !== false) {
                    $mark ^= 1; // 目录可读 001，目录不可读 000
                }
                @closedir($dir);
                /* 检查目录是否可写 */
                $fp = @fopen($test_file, 'wb');
                if ($fp === false) {
                    return $mark; // 如果目录中的文件创建失败，返回不可写。
                }
                if (@fwrite($fp, 'directory access testing.') !== false) {
                    $mark ^= 2; // 目录可写可读011，目录可写不可读 010
                }
                @fclose($fp);
                @unlink($test_file);
                /* 检查目录是否可修改 */
                $fp = @fopen($test_file, 'ab+');
                if ($fp === false) {
                    return $mark;
                }
                if (@fwrite($fp, "modify test.\r\n") !== false) {
                    $mark ^= 4;
                }
                @fclose($fp);
                /* 检查目录下是否有执行rename()函数的权限 */
                if (@rename($test_file, $test_file) !== false) {
                    $mark ^= 8;
                }
                @unlink($test_file);
            } /* 如果是文件 */
            elseif (is_file($file_path)) {
                /* 以读方式打开 */
                $fp = @fopen($file_path, 'rb');
                if ($fp) {
                    $mark ^= 1; // 可读 001
                }
                @fclose($fp);
                /* 试着修改文件 */
                $fp = @fopen($file_path, 'ab+');
                if ($fp && @fwrite($fp, '') !== false) {
                    $mark ^= 6; // 可修改可写可读 111，不可修改可写可读011...
                }
                @fclose($fp);
                /* 检查目录下是否有执行rename()函数的权限 */
                if (@rename($test_file, $test_file) !== false) {
                    $mark ^= 8;
                }
            }
        } else {
            if (@is_readable($file_path)) {
                $mark ^= 1;
            }
            if (@is_writable($file_path)) {
                $mark ^= 14;
            }
        }
        return $mark;
    }

    /**
     * +-----------------------------------------------------------------------------------------
     * 遍历获取目录下的指定类型的文件
     * +-----------------------------------------------------------------------------------------
     *
     * @param $path 路径
     * @param array $files
     *            文件类型数组
     *            +-----------------------------------------------------------------------------------------
     * @return array 所有文件路径
     *         +-----------------------------------------------------------------------------------------
     */
    function getfiles($path, &$files = array())
    {
        if (!is_dir($path))
            return null;
        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $path2 = $path . '/' . $file;
                if (is_dir($path2)) {
                    $this->getfiles($path2, $files);
                } else {
                    if (preg_match("/\.(gif|jpeg|jpg|png|bmp)$/i", $file)) {
                        $files [] = $path2;
                    }
                }
            }
        }
        return $files;
    }

    /**
     * +----------------------------------------------------------
     * 功能：检测一个目录是否存在，不存在则创建它
     * +----------------------------------------------------------
     *
     * @param string $path
     *            待检测的目录
     *            +----------------------------------------------------------
     * @return boolean +----------------------------------------------------------
     */
    function makeDir($path)
    {
        return is_dir($path) or ($this->makeDir(dirname($path)) and @mkdir($path, 0777));
    }
}