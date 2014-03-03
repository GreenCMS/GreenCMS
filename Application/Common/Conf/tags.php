<?php
/**
 * Created by Green Studio.
 * File: config_tags.php
 * User: TianShuo
 * Date: 14-2-16
 * Time: ����6:08
 */
return array(
    'app_init'=>array('Common\Behavior\InitHookBehavior'),

    'app_end' => array(
        'Behavior\ChromeShowPageTraceBehavior'
    )
);