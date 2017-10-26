<?php



// 判断是否是微信内部浏览器
function is_weixin(){
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            return true;
        }
            return false;
}


function createqrcode($uid){
   
        ob_clean();  
        $object =new \QRcode();
        $url = WAB_NAME.'/register/index/'.$this->uid;//网址或者是文本内容
        $level=3;
        $size=4;
        $errorCorrectionLevel =intval($level) ;//容错级别
        $matrixPointSize = intval($size);//生成图片大小       
        $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2); 
  
    }