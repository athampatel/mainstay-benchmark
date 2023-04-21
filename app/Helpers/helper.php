<?php 
namespace App\Helpers;

class Helper {
    public static function parse_size($size) {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);
        if ($unit) {
        return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }
        else {
        return round($size);
        }
    }
}
?>