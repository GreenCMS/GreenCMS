<?php
/**
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-9-24
 * Time: 下午8:50
 */

namespace Common\Event;


interface DBInfoEvent
{

    public function getTotalLength();

    public function setTotalLength($total_length);

    public function getTotalLengthFormated();

    public function getTabs();

    public function setTabs($tabs);

    public function countTabs();

} 