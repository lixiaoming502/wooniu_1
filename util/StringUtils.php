<?php
/**
 * Created by PhpStorm.
 * User: lixiaoming
 * Date: 2017/2/19
 * Time: 下午3:54
 */
class StringUtils{

    static function startwith($str,$pattern) {
        if(strpos($str,$pattern) === 0)
            return true;
        else
            return false;
    }

}
