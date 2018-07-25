<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/24
 * Time: 9:39
 */

function post($param)
{
    if (1===count($param)){
        return $data = $_POST[$param[0]];
    }

    foreach ($param as $value){
        $data[$value] = $_POST[$value];
    }
    return $data;
}