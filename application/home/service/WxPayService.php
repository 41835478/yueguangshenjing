<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14
 * Time: 10:43
 */
namespace app\home\service;
use think\Model;

class WxPayService extends Model
{
    //查询订单的真实性
    public function wxorderquery($transaction_id){
        $data = array();
        $data['appid'] = config('APPID');
        $data['mch_id'] = config('MCH_ID');
        $data['nonce_str'] = time().rand(10000,99999);
        $data['transaction_id'] = $transaction_id;
        $sign = $this->getSign($data,config('KEY'));
        $xmlStr = '<xml>
			<appid><![CDATA['.$data['appid'].']]></appid>
			<mch_id><![CDATA['.$data['mch_id'].']]></mch_id>
			<nonce_str><![CDATA['.$data['nonce_str'].']]></nonce_str>
			<transaction_id><![CDATA['.$data['transaction_id'].']]></transaction_id>
			<sign><![CDATA['.$sign.']]></sign>
		</xml>';
        $sendUrl = 'https://api.mch.weixin.qq.com/pay/orderquery';
        $result = $this->postXmlCurl($xmlStr,$sendUrl);
        $result = $this->xmlToArray($result);
        if( ($result['result_code'] == 'SUCCESS') || ($result['result_code'] == 'success') ){
            return $result;
        }else{
            return 'ERROR';
        }
    }

    public function postXmlCurl($xml, $url, $useCert = false, $second = 30){
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        //如果有配置代理这里就设置代理
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if($useCert == true){
            //设置证书
            curl_setopt($ch,CURLOPT_SSLCERT,'./Data/WxCert/apiclient_cert.pem');
            curl_setopt($ch,CURLOPT_SSLKEY,'./Data/WxCert/apiclient_key.pem');
            curl_setopt($ch,CURLOPT_CAINFO,'./Data/WxCert/rootca.pem');
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        }else{
            $error = curl_errno($ch);
            curl_close($ch);
            return "curl出错，错误码:$error";
        }
    }

    public function xmlToArray($xml){
        //将XML转为array
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }

    //签名
    public function getSign($data,$key){
        ksort($data);
        $result = '';
        $i = 0;
        foreach($data as $k=>$v){
            $i++;
            if($i!=1){
                $result .= '&';
            }
            $result .= $k . '=' . $v;
        }
        $result .= '&key=' . $key;
        //return $result;
        return strtoupper(md5($result));
    }
}