<?php
namespace App\Utils;

class DateUtil
{
    public static function strdateToTimestamp($strDate) {
        list($d,$m,$y) = explode('/', $strDate);
        return mktime(0,0,0,$m,$d,$y);
    }

    public static function strdateToDate($strDate) {
        return date('Y-m-d', DateUtil::strdateToTimestamp($strDate));
    }
}