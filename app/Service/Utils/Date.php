<?php

namespace App\Service\Utils;

/**
 * Date处理相关封装
 *
 */
class Date
{
    /**
     * 计算时间差
     * 
     * @param string $start
     * @param string $end
     * @return string 返回格式 1天5小时5分
     */
    public static function getDiff($start, $end)
    {
        return \Carbon\Carbon::parse($start)->diffForHumans($end);
    }

    /**
     * 获取周的开始时间   处理mysql日期转换的
     *
     * @author Ming
     * @date   2018-09-25
     *
     * @param  array     $date  年-周   例如：2017-00 2017-51
     */
    public static function getWeekDay($date)
    {
        $arr = explode('-',$date);
        if ($arr[1] == '00') return $arr[0]."-01-01";
        return date("Y-m-d",strtotime($arr[0].'-W'.$arr[1]));
    }

    public static function vtime($time){
        $output = '';
        foreach (array(86400 => '天', 3600 => '小时', 60 => '分', 1 => '秒') as $key => $value) {
          if ($time >= $key) $output .= floor($time/$key) . $value;
          $time %= $key;
        }
        return $output;
    }

    public static function time_format($date)
    {
        if (date('Y-m-d',strtotime($date)) == date('Y-m-d')) return '今天 '.date('H:i:s', strtotime($date));
        if (date('Y-m-d',strtotime($date)) == date('Y-m-d',strtotime('-1 day'))) return '昨天 '.date('H:i:s', strtotime($date));
        return date('Y-m-d',strtotime($date));
    }

    /**
     * 圈子时间格式
     *
     * @author Ming 2019-10-11
     *
     * @param  [type] $date [description]
     */
    public static function quanziTimeFormat($date)
    {
        if (date('Y-m-d',strtotime($date)) == date('Y-m-d')) return '今天 '.date('H:i', strtotime($date));
        if (date('Y-m-d',strtotime($date)) == date('Y-m-d',strtotime('-1 day'))) return '昨天 '.date('H:i', strtotime($date));
        if (date('Y',strtotime($date)) == date('Y')) return date('m-d H:i', strtotime($date));
        return date('Y-m-d H:i',strtotime($date));
    }

    public static function messageTimeShow($date)
    {
        if (date('Y-m-d',strtotime($date)) == date('Y-m-d')) return '今天 '.date('H:i', strtotime($date));
        if (date('Y-m-d',strtotime($date)) == date('Y-m-d',strtotime('-1 day'))) return '昨天 '.date('H:i', strtotime($date));
        if (date('Y',strtotime($date)) == date('Y')) return date('m-d H:i', strtotime($date));
        return date('Y-m-d H:i',strtotime($date));
    }
}