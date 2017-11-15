<?php

namespace app\home\controller;

use think\Controller;
use think\Request;

class Wxpay extends Controller
{
    public function index(Request $request)
    {
        $getDate=$request->param();
        $orderId=$getDate['order_id'];
        $openid=isset($getDate['openid'])?$getDate['openid']:'';
        $order=model('admin/OrderModel')->get($orderId);
        if($order&&$order->status==1&&($order->price>0)){
            if($openid) {
                $date['pay'] = 1;//$order->price*100;
                //$appid = C('APPID');
                $key = config('KEY');
                //var_dump($key);
                $data = array();
                $data['appid'] = config('APPID');
                $data['mch_id'] = config('MCH_ID');
                $data['attach'] = '微信支付';
                $data['body'] = '越光神镜';
                $data['detail'] = '商品详情';
                $data['nonce_str'] = time() . rand(10000, 99999);
                $data['notify_url'] = config('WX_NOTIFY_URL');
                $data['openid'] = $openid;
                $data['out_trade_no'] = $order->order_code;
                $data['spbill_create_ip'] = $request->ip();//终端ip
                $data['total_fee'] = $date['pay'];
                $data['trade_type'] = 'JSAPI';
                $sign = model('WxPayService','service')->getSign($data, $key);//签名

                $xmlStr = '<xml>
                   <appid><![CDATA[' . $data['appid'] . ']]></appid>
                   <mch_id><![CDATA[' . $data['mch_id'] . ']]></mch_id>
                   <openid><![CDATA[' . $data['openid'] . ']]></openid>
                   <attach><![CDATA[' . $data['attach'] . ']]></attach>
                   <body><![CDATA[' . $data['body'] . ']]></body>
                   <detail><![CDATA[' . $data['detail'] . ']]></detail>
                   <nonce_str><![CDATA[' . $data['nonce_str'] . ']]></nonce_str>
                   <notify_url><![CDATA[' . $data['notify_url'] . ']]></notify_url>
                   <out_trade_no><![CDATA[' . $data['out_trade_no'] . ']]></out_trade_no>
                   <spbill_create_ip><![CDATA[' . $data['spbill_create_ip'] . ']]></spbill_create_ip>
                   <total_fee><![CDATA[' . $data['total_fee'] . ']]></total_fee>
                   <trade_type><![CDATA[' . $data['trade_type'] . ']]></trade_type>
                   <sign><![CDATA[' . $sign . ']]></sign>
                </xml>';
                $sendUrl = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
                $result = model('WxPayService','service')->postXmlCurl($xmlStr, $sendUrl);
                $resultArr = model('WxPayService','service')->xmlToArray($result);

                if (!array_key_exists("appid", $resultArr) || !array_key_exists("prepay_id", $resultArr) || $resultArr['prepay_id'] == "") {
                    echo '下单失败 ' . $resultArr['return_code'] . '：' . $resultArr['return_msg'];
                    die;
                }
                $parametersArr = array();
                $parametersArr['appId'] = config('APPID');
                $parametersArr['timeStamp'] = strval(time());
                $parametersArr['nonceStr'] = $resultArr['nonce_str'];
                $parametersArr['package'] = 'prepay_id=' . $resultArr['prepay_id'];
                $parametersArr['signType'] = 'MD5';
                $sign = model('WxPayService','service')->getSign($parametersArr, $key);
                $parametersArr['paySign'] = $sign;
                $jsApiParameters = json_encode($parametersArr);
                return view('wxPay/wxChatPay', ['jsApiParameters'=>$jsApiParameters]);
            }else{
                $this->redirect(url('wxpay/getOpenId',['id'=>$orderId]));
            }
        }else{
            $this->redirect(url('users/index'));
        }
    }

    public function getOpenId(Request $request)//获取用户openid
    {
        $id=$request->param('id');
        $code =$request->param('code');
        if (!$code) {
            $url = urlencode(config('WEB').url('wxpay/getOpenId',['id'=>$id]));
            $sendUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . config('APPID') . '&redirect_uri=' . $url . '&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
            return redirect($sendUrl);
        }
        $tokenUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . config('APPID') . '&secret=' . config('SECRET') . '&code=' . $code . '&grant_type=authorization_code';
        $access_token_contents = $this->https_request($tokenUrl);
        $access_token = json_decode($access_token_contents, true);
        $openid=$access_token['openid'];
//        exit('<script>window.location.href="/home/mobileWxPay/wxChatPay?id='.$id.'&openid='.$openid.'"</script>');
        exit('<script>window.location.href="'.config('WEB').'/home/wxpay/index?order_id='.$id.'&openid='.$openid.'"</script>');
    }

    public function https_request($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl,  CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)){
            return 'ERROR'.curl_error($curl);
        }
        curl_close($curl);
        return $data;
    }
}
