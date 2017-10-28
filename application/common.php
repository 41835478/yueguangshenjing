<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

#判断设备类型
function isMobile()
{ 
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
    {
        return true;
    } 
    if (isset ($_SERVER['HTTP_VIA']))
    { 
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    } 
    if (isset ($_SERVER['HTTP_USER_AGENT']))
    {
        $clientkeywords = array ('nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
            ); 
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
        {
            return true;
        } 
    } 
    if (isset ($_SERVER['HTTP_ACCEPT']))
    { 
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
        {
            return true;
        } 
    } 
    return false;
}
//获取用户所有上级
function getUpUser($arr,$prentId){
    $totalNum = [];
    while($prentId !== 0 ){
        foreach($arr as $key=>$val){
            if($val['id'] == $prentId){
                array_push($totalNum,$val['id']);
                $prentId = $val['pid'];
            }
        }
    }
    return $totalNum;
}
//获取所有下级
function getChildenAll($uid,$users,$userall = '',$class=''){
    if(empty($userall)){
        static $userall = [];
    }else{
        static $userall = [];
        $userall = [];
    }
    if(!in_array($uid, $userall)) {
        if(is_array($uid)){
            foreach($uid as $v){
                $userall[] = $v;
            }
        }else{
            array_push($userall, $uid);
        }
    }
    $userChildren = [];
    foreach($users as $k=>$v){
        if(is_array($uid)){
            if(in_array($v['pid'],$uid)){
                array_push($userChildren,$v['id']);
            }
        }else{
            if($v['pid'] == $uid){
                array_push($userChildren,$v['id']);
            }
        }
    }
    $userall = array_unique(array_merge($userall, $userChildren));
    if(!empty($userChildren)){
        if($class){
            $class--;
            if($class > 0){
                getChildenAll($userChildren,$users,'',$class);
            }
        }else{
            getChildenAll($userChildren,$users);
        }
    }
    sort($userall);

    // dump($userall);
    return $userall;
}