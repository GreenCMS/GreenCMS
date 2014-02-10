<?php
/**
 * Created by Green Studio.
 * File: QRMaker.class.php
 * User: TianShuo
 * Date: 14-1-23
 * Time: 上午11:29
 */

namespace Common\Util;


/**
 * Class QRMaker
 * @package Common\Util
 */
class QRMaker
{

    /**
     * @var string api地址
     */
    protected $api = 'http://chart.googleapis.com/';

    /**
     * @var string 数据
     */
    protected $data = '';

    /**
     * @var null  logo图片
     */
    protected $logo = null;

    /**
     * @var null|string 大小
     */
    protected $size = null;

    /**
     * @var QR
     */
    protected $QR = null;


    /**
     * @function 构造函数
     *
     * @param $data
     * @param $logo logo图片
     * @param string $size
     */
    public function __construct($data, $logo, $size = '200x200')
    {
        $this->data = $data;
        $this->logo = $logo;
        $this->size = $size;
    }

    /**
     * @function 获得二维码图片
     * @return string
     */
    protected function getpng()
    {
        return $this->api = $this->api . 'chart?chs=' . $this->size . '&cht=qr&chl=' . urlencode($this->data) . '&chld=L|1&choe=UTF-8';
    }

    /**
     * @function 输出二维码图片
     */
    public function images()
    {
        $this->QR = imagecreatefrompng($this->getpng());
        if ($this->logo !== false) {
            $this->logo = imagecreatefromstring(file_get_contents($this->logo));

            $QR_width = imagesx($this->QR);
            $QR_height = imagesy($this->QR);

            $logo_width = imagesx($this->logo);
            $logo_height = imagesy($this->logo);

            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;

            imagecopyresampled($this->QR, $this->logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        }
        header('Content-type: image/png');
        imagepng($this->QR);
        imagedestroy($this->QR);
    }

}