<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: config_tags.php
 * User: Timothy Zhang
 * Date: 14-2-16
 * Time: ����6:08
 */
return array(
    'app_init' => array(
        'Common\Behavior\InitHookBehavior'
    ),

    'app_end' => array(
        'Behavior\ChromeShowPageTraceBehavior'
    )
);