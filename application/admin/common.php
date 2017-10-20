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

function getUserInfo(Request $request,$userId)//利用tp5的方法注入得到用户的信息
{
    $res=model('Admin')->get($userId);
    return $res;
}
//判断url是否合法
function isUrl($url){
    $pattern_1 = "/^(http|https|ftp):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+.(com|org|net|dk|at|us|tv|info|uk|co.uk|biz|se)$)(:(\d+))?\/?/i";
    $pattern_2 = "/^(www)((\.[A-Z0-9][A-Z0-9_-]*)+.(com|org|net|dk|at|us|tv|info|uk|co.uk|biz|se)$)(:(\d+))?\/?/i";
    if(preg_match($pattern_1, $url) || preg_match($pattern_2, $url)){
        return true;
    } else{
        return false;
    }
}
?>