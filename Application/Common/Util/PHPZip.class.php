<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: Zip.class.php
 * User: Timothy Zhang
 * Date: 14-2-8
 * Time: 下午9:47
 */

namespace Common\Util;

/**
 * Class PHPZip
 * @package Common\Util
 */
class PHPZip
{
    /**
     * @param $folder
     * @param \ZipArchive $zipFile
     * @param null $subfolder
     * @return bool
     */
    public static function folderToZip($folder, &$zipFile, $subfolder = null)
    {
        if ($zipFile == null) {
            // no resource given, exit
            return false;
        }
        if (is_file($folder)) {
            $zipFile->addFile($folder);
        }
        // we check if $folder has a slash at its end, if not, we append one
        $folder .= end(str_split($folder)) == "/" ? "" : "/";
        $subfolder .= end(str_split($subfolder)) == "/" ? "" : "/";

        // we start by going through all files in $folder
        $handle = opendir($folder);
        while ($f = readdir($handle)) {
            if ($f[0] != ".") {
                if (is_file($folder . $f)) {
                    // if we find a file, store it
                    // if we have a subfolder, store it there
                    if ($subfolder != null) {
                        $zipFile->addFile($folder . $f, $folder . $f);
                    } else
                        $zipFile->addFile($folder . $f);

                } elseif (is_dir($folder . $f)) {
                    // if we find a folder, create a folder in the zip
                    // $zipFile->addEmptyDir($f);
                    if ($subfolder != null) {
                        $zipFile->addEmptyDir($folder . $f);
                    } else {
                        $zipFile->addEmptyDir($folder . $f);
                    }
                    self::folderToZip($folder . $f, $zipFile, $folder);

                    // and call the function again
                }
            }
        }
    }

}

?>