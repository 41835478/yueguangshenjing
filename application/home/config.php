<?php
//配置文件
return [
    'WEB'=>'http://ygsj.ewtouch.com',
    //支付宝支付
    'RETURN_URL'=>'http://ygsj.ewtouch.com/home/ali_pay/return_url',//同步回调地址
    'NOTIFY_URL'=>'http://ygsj.ewtouch.com/home/ali_pay/notify_url',//异步回调地址

    //微信支付
    'KEY'=>'ce63859efc6d8ca2162aba866b59aa24',
    'APPID'=>'wxecf9bd4790f84ec9',
    'MCH_ID'=>'1491951332',
    'WX_NOTIFY_URL'=>'http://ygsj.ewtouch.com/home/Wxcallback/paynotify',//微信支付回调
    'SECRET'=>'fcb3d3ee62f52cfd099bac546567b3c5',
];