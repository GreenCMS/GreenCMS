<?php
/**
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-9-24
 * Time: 下午8:43
 */

namespace Common\Event;


use Common\Util\File;

class MySQLInfoEvent implements DBInfoEvent
{

    private $total_length;

    private $tabs;

    function __construct()
    {
        $this->tabs = M()->query('SHOW TABLE STATUS');
        $this->total_length = 0;
        foreach ($this->tabs as $key => $value) {
            $this->tabs[$key]['size'] = File::byteFormat($value['Data_length'] + $value['Index_length']);
            $this->total_length += $value['Data_length'] + $value['Index_length'];
        }
    }

    /**
     * @return mixed
     */
    public function getTotalLength()
    {
        return $this->total_length;
    }

    /**
     * @param mixed $total_length
     */
    public function setTotalLength($total_length)
    {
        $this->total_length = $total_length;
    }

    /**
     * @return mixed
     */
    public function getTotalLengthFormated()
    {
        return File::byteFormat($this->total_length);
    }

    /**
     * @return mixed
     */
    public function getTabs()
    {
        return $this->tabs;
    }

    /**
     * @param mixed $tabs
     */
    public function setTabs($tabs)
    {
        $this->tabs = $tabs;
    }

    public function countTabs()
    {
        return count($this->tabs);
    }


}