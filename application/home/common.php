<?php



// 判断是否是微信内部浏览器
function is_weixin(){
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            return true;
        }
            return false;
}


