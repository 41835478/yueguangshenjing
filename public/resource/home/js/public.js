/*
* @Author: WangYa
* @Date:   2017-09-21 14:23:49
* @Last Modified by:   WangYa
* @Last Modified time: 2017-09-29 10:44:38
*/
$(function(){
	$(".QR_li").click(function() {
		$(".popBox").fadeIn('fast');
	});
	$(".popBox .btn1").click(function() {
		$(".popBox").fadeOut('fast');
	});


	$(".popBox .btn2").click(function() {
		window.location.href = '/';
	});
});
(function() {
        if (typeof WeixinJSBridge == "object" && typeof WeixinJSBridge.invoke == "function") {
            handleFontSize();
        } else {
            if (document.addEventListener) {
                document.addEventListener("WeixinJSBridgeReady", handleFontSize, false);
            } else if (document.attachEvent) {
                document.attachEvent("WeixinJSBridgeReady", handleFontSize);
                document.attachEvent("onWeixinJSBridgeReady", handleFontSize);
            }
        }
        function handleFontSize() {
            // 设置网页字体为默认大小
            WeixinJSBridge.invoke('setFontSizeCallback', { 'fontSize' : 0 });
            // 重写设置网页字体大小的事件
            WeixinJSBridge.on('menu:setfont', function() {
                WeixinJSBridge.invoke('setFontSizeCallback', { 'fontSize' : 0 });
            });
        }
    })();
