<?php
use think\Request;
function getTime()
{
    $time = time();
    $daybreak = strtotime('24:00:00');
    $morning = strtotime('09:00:00');
    $noonday = strtotime('12:00:00');
    $moddle = strtotime('13:00:00');
    $afternoon = strtotime('18:00:00');
    if ($time <= $morning) {
        return '早上好';
    }
    if ($time > $morning && $time <= $noonday) {
        return '上午好';
    }
    if ($time > $noonday && $time <= $moddle) {
        return '中午好';
    }
    if ($time > $moddle && $time <= $afternoon) {
        return '下午好';
    }
    if ($time > $noonday && $time <= $daybreak) {
        return '晚上好';
    }
}

function popBox($status,$message)
{
    \think\Session::flash('status',strtolower($status));
    \think\Session::flash('value',$message);
    $Url=$_SERVER['HTTP_REFERER'];
    return $Url;
}
?>