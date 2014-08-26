<?php
return array(
    'openReg' => array( //配置在表单中的键名 ,这个会是config[random]
        'title' => '是否开启:', //表单的文字
        'type' => 'radio', //表单的类型：text、textarea、checkbox、radio、select等
        'options' => array( //select 和radion、checkbox的子选项
            '1' => '开启', //值=>文字
            '0' => '关闭',
        ),
        'value' => '0', //表单的默认值
    ),

    'endTime' => array(

        'title' => '截止日期:',
        'type' => 'text',
        'value' => '2014-1-30 06:00:00', //表单的默认值
    ),


);
                                        