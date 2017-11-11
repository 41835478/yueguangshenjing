<?php

namespace app\home\controller;

use app\admin\model\AccountRecordModel;
use think\Controller;
use think\Db;
use think\Exception;
use think\Log;
use think\Request;
use think\Session;

class AliPay extends Controller
{
    //HTTPS形式消息验证地址
    public $https_verify_url="https://mapi.alipay.com/gateway.do?service=notify_verify&";
    public $http_verify_url="http://notify.alipay.com/trade/notify_query.do?";//HTTP形式消息验证地址
    public $alipay_gateway_new="https://mapi.alipay.com/gateway.do?";//支付宝网关

    //商户的私钥,此处填写原始私钥去头去尾
    private $private_key	= "MIICXgIBAAKBgQDTYrLP06IJKfsz5enYoB9JTfbNya6K2AEHJCf2jsMiI9poJ8jp
QtGROQzMSji9BsspeAIBzyL3HAOTlii1/+EiZ3jIbMPABIGmUhCBQYv5J70IJFsL
1/GxhkxUgUGJ9nJW/hMy1kbrmSsaiOUiylVx6YHRNywnMaRPV8FFo4d8cwIDAQAB
AoGBAIsBt6bVfBPcqJW7VqkeuxRAkdFIFGC3pQ1mKGWKAiY8P48IwqUqTJfcVV8u
W9tqUUWG5oL8DPlFUdDHCSSODSSZU7kHJBwKrnY9dMx2QTFKgxbcekVx8KEEHs4p
9vglPtONxQzrNTVkO70lg0S2QhqwJGp6g6feq34lnM1dfaLhAkEA9XVYRCi9Ycqt
S5cHjZtfZ4aRF+2iLTQaQ5Wi8lMPU4aQltHLHgR6BELv63XfCEyV6gJZOtTivxuF
yT7fOXDaowJBANx2vrkY69g/8JW8wl+lZtoadI5oWi4NP2oDq2TVVwY4vGodchn8
MVAva+gtIeDk+0buzNKNM3sGyJyQXYa7Q/ECQQCUq9mllwN2SvuB8F170OMNYn1Z
f+6d8JQD4YuLuPFshvHm1/VuHgoFsMF+3asUbMbUsU14i983H6V+Cl3MfeV/AkB+
/iu4xaE9LP+x0UyJ+eX4JpsTWQrxUhJASCLXb4i0fcQSwCl0zG+/h8F5uV21hVE1
N+NmOY35BRxUC1jEfmSRAkEAvBJ2V1rINZfKw55dbSwEAl0ri8LPRXNGrP8zNYY3
n3ZDxxsEHhn+zZh0oAgtVQDx6ebwHVaCTVNhHMNeLmim1w==";
    //支付宝的公钥
    private $alipay_public_key= "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnxj/9qwVfgoUh/y2W89L6BkRAFljhNhgPdyPuBV64bfQNN1PjbCzkIM6qRdKBoLPXmKKMiFYnkd6rAoprih3/PrQEB/VsW8OoM8fxn67UDYuyBTqA23MML9q1+ilIZwBC2AQ2UBVOrFXfFl75p6/B5KsiNG9zpgmLCUYuLkxpLQIDAQAB";
    private $partner="2088821685612250";//签约的支付宝账号对应的支付宝唯一用户号。以2088开头的16位纯数字组成。
    private $cacert="http://ygsj.ewtouch.com/Data/AliPayCert/cacert.pem";//证书demo中有

    public function index($id)
    {
        if($id){
            $order=model('admin/OrderModel')->get($id);
            if($order&&$order->status==1&&($order->price>0)){
                $data['out_trade_no'] = $order->order_code;//商户订单号
                $data['subject'] = '越光神镜';//商品的标题/交易标题/订单标题/订单关键字等。
                $data['total_fee'] = $order->price;//$find->total_money;//支付金额
                echo $res1=$this->alipay($data);
            }else{
                $this->redirect(url('users/index'));
            }
        }else{
            $this->redirect(url('users/index'));
        }
    }

    public function alipay($data)
    {
        $param=array(
            'service' => 'alipay.wap.create.direct.pay.by.user',//接口名称
            'partner' => $this->partner,
            'seller_id'=>$this->partner,//收款支付宝用户ID。 如果该值为空，则默认为商户签约账号对应的支付宝用户ID
            '_input_charset' => 'UTF-8',
            'notify_url' => config('NOTIFY_URL'),//异步通知页面
            'return_url' => config('RETURN_URL'),//同步通知页面
            'out_trade_no' => $data['out_trade_no'],//订单号
            'subject' => $data['subject'],//商品名称
            'payment_type' => '1',// 支付类型。默认值为：1（商品购买）。
            'total_fee' => 0.01,//$data['total_fee']
            'seller_email' => 'yueguangshenjing@163.com',//卖家支付宝账号，可以是email和手机号码。
        );


        $from=$this->buildRequestForm($param,'get','提交');
        return $from;
    }

    /**
     * 针对notify_url验证消息是否是支付宝发出的合法消息
     * @return bool
     */
    public function verifyNotify()
    {
        if(empty($_POST)){
            return false;
        }else{
            //生成签名结果
            $isSign=$this->getSignVeryfy($_POST,$_POST['sign']);
            //获取支付宝远程服务器ATN结果（验证是否是支付宝发过来的消息）
            $responseTxt='false';
            if(!empty($_POST['notify_id'])){
                $responseTxt=$this->getResponse($_POST['notify_id']);
            }
            if(preg_match("/true$/i",$responseTxt)&&$isSign){
                return true;
            }else{
                return false;
            }
        }
    }

    /**
     * 针对return_url验证消息是否是支付宝发出的合法消息
     * @return bool
     */
    public function verifyReturn(){
        if(empty($_GET)) {//判断POST来的数组是否为空
            return false;
        }
        else {
            //生成签名结果
            $isSign = $this->getSignVeryfy($_GET, $_GET["sign"]);
            //获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
            $responseTxt = 'false';
            if (! empty($_GET["notify_id"])) {
                $responseTxt = $this->getResponse($_GET["notify_id"]);
            }}
        if (preg_match("/true$/i",$responseTxt) && $isSign) {
            return true;
        } else {
            return false;
        }
    }

//=======================================================================RSA获取返回时的签名验证结果===================================================================


    /**
     * 获取返回时的签名验证结果
     * @param $para_temp 通知返回来的参数数组
     * @param $sign 返回的签名结果
     * @return 签名验证结果
     */
    function getSignVeryfy($para_temp, $sign) {
        //除去待签名参数数组中的空值和签名参数
        $para_filter = $this->paraFilter($para_temp);

        //对待签名参数数组排序
        $para_sort = $this->argSort($para_filter);

        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = $this->createLinkstring($para_sort);

        $isSgin = false;
        switch (strtoupper(trim(strtoupper('RSA')))) {
            case "RSA" :
                $isSgin = $this->rsaVerify($prestr, trim($this->alipay_public_key), $sign);
                break;
            default :
                $isSgin = false;
        }

        return $isSgin;
    }

//=======================================================================RSA验签=====================================================================================


    /**
     * RSA验签
     * @param $data 待签名数据
     * @param $alipay_public_key 支付宝的公钥字符串
     * @param $sign 要校对的的签名结果
     * return 验证结果
     */
    function rsaVerify($data, $alipay_public_key, $sign)  {
        //以下为了初始化私钥，保证在您填写私钥时不管是带格式还是不带格式都可以通过验证。
        $alipay_public_key=str_replace("-----BEGIN PUBLIC KEY-----","",$alipay_public_key);
        $alipay_public_key=str_replace("-----END PUBLIC KEY-----","",$alipay_public_key);
        $alipay_public_key=str_replace("\n","",$alipay_public_key);

        $alipay_public_key='-----BEGIN PUBLIC KEY-----'.PHP_EOL.wordwrap($alipay_public_key, 64, "\n", true) .PHP_EOL.'-----END PUBLIC KEY-----';
        $res=openssl_get_publickey($alipay_public_key);
        if($res)
        {
            $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        }
        else {
            echo "您的支付宝公钥格式不正确!"."<br/>"."The format of your alipay_public_key is incorrect!";
            exit();
        }
        openssl_free_key($res);
        return $result;
    }

    /**
     * 获取远程服务器ATN结果,验证返回URL
     * @param $notify_id
     * @return mixed
     */
    public function getResponse($notify_id) {
        $transport = strtolower(trim('http'));
        $partner = trim($this->partner);
        $veryfy_url = '';
        if($transport == 'https') {
            $veryfy_url = $this->https_verify_url;
        }
        else {
            $veryfy_url = $this->http_verify_url;
        }
        $veryfy_url = $veryfy_url."partner=" . $partner . "&notify_id=" . $notify_id;
        $responseTxt = $this->getHttpResponseGET($veryfy_url, $this->cacert);

        return $responseTxt;
    }

    /**
     * 远程获取数据，GET模式
     * @param $url
     * @param $cacert_url
     * @return mixed
     */
    function getHttpResponseGET($url,$cacert_url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
        curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
        $responseText = curl_exec($curl);
//        var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
        curl_close($curl);

        return $responseText;
    }

    /**
     * 建立请求，以表单HTML形式构造（默认）
     * @param $para_temp
     * @param $method
     * @param $button_name
     * @return string
     */
    public function buildRequestForm($para_temp, $method, $button_name)
    {


        //待请求参数数组
        $para = $this->buildRequestPara($para_temp);

        $sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='" . $this->alipay_gateway_new . "_input_charset=" . trim(strtolower('utf-8')) . "' method='" . $method . "'>";
        while (list ($key, $val) = each($para)) {
            $sHtml .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
        }

        //submit按钮控件请不要含有name属性
        $sHtml = $sHtml . "<input type='submit'  value='" . $button_name . "' style='display:none;'></form>";

        $sHtml = $sHtml . "<script>document.forms['alipaysubmit'].submit();</script>";

        return $sHtml;
    }

    /**
     * 生成要请求给支付宝的参数数组
     * @param $para_temp 请求前的参数数组
     * @return 要请求的参数数组
     */
    public function buildRequestPara($para_temp)
    {

        //除去待签名参数数组中的空值和签名参数
        $para_filter = $this->paraFilter($para_temp);

        //对待签名参数数组排序
        $para_sort = $this->argSort($para_filter);

        //生成签名结果
        $mysign = $this->buildRequestMysign($para_sort);

        //签名结果与签名方式加入请求提交参数组中
        $para_sort['sign'] = $mysign;
        $para_sort['sign_type'] = strtoupper(trim('RSA'));

        return $para_sort;
    }

    /**
     * 除去数组中的空值和签名参数
     * @param $para 签名参数组
     * @return 去掉空值与签名参数后的新签名参数组
     */
    public function paraFilter($para)
    {
        $para_filter = array();
        while (list ($key, $val) = each($para)) {
            if ($key == "sign" || $key == "sign_type" || $val == "") continue;
            else    $para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }

    /**
     * 对数组排序
     * @param $para 排序前的数组
     * @return 排序后的数组
     */
    public function argSort($para)
    {
        ksort($para);
        reset($para);
        return $para;
    }


//=============================================================这里是RSA生成签名结果========================================================================
    /**
     * 生成签名结果
     * @param $para_sort 已排序要签名的数组
     * return 签名结果字符串
     */
    function buildRequestMysign($para_sort) {
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = $this->createLinkstring($para_sort);

        $mysign = "";
        switch (strtoupper(trim('RSA'))) {
            case "RSA" :
                $mysign = $this->rsaSign($prestr, $this->private_key);
                break;
            default :
                $mysign = "";
        }

        return $mysign;
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param $para 需要拼接的数组
     * @return 拼接完成以后的字符串
     */
    public function createLinkstring($para)
    {
        $arg = "";
        while (list ($key, $val) = each($para)) {
            $arg .= $key . "=" . $val . "&";
        }
        //去掉最后一个&字符
        $arg = substr($arg, 0, count($arg) - 2);

        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $arg = stripslashes($arg);
        }

        return $arg;
    }

//============================================================RSA签名======================================================
    /**
     * RSA签名
     * @param $data 待签名数据
     * @param $private_key 商户私钥字符串
     * return 签名结果
     */
    function rsaSign($data, $private_key) {
        //以下为了初始化私钥，保证在您填写私钥时不管是带格式还是不带格式都可以通过验证。
        $private_key=str_replace("-----BEGIN RSA PRIVATE KEY-----","",$private_key);
        $private_key=str_replace("-----END RSA PRIVATE KEY-----","",$private_key);
        $private_key=str_replace("\n","",$private_key);

        $private_key="-----BEGIN RSA PRIVATE KEY-----".PHP_EOL .wordwrap($private_key, 64, "\n", true). PHP_EOL."-----END RSA PRIVATE KEY-----";

        $res=openssl_get_privatekey($private_key);

        if($res)
        {
            openssl_sign($data, $sign,$res);
        }
        else {
            echo "您的私钥格式不正确!"."<br/>"."The format of your private_key is incorrect!";
            exit();
        }
        openssl_free_key($res);
        //base64编码
        $sign = base64_encode($sign);
        return $sign;
    }


    /**
     * 支付宝同步通知（页面会跳转到我们自己设定的页面）
     */
    public function return_url()
    {
        $verify=$this->verifyReturn();
        if($verify){
            $trade_statue = $_GET['trade_status'];//支付结果
            if ($trade_statue == 'TRADE_FINISHED' || $trade_statue == 'TRADE_SUCCESS') {
                $this->redirect(url('pay/paySuccess'));
            }else{
                $this->redirect(url('users/index'));
            }
        }else{
            $this->redirect(url('users/index'));
        }
    }

    /**
     * 支付宝异步通知
     */
    public function notify_url()
    {
        $verify=$this->verifyNotify();
        if($verify){
            $out_trade_no = $_POST['out_trade_no'];//商户订单号

            $trade_no = $_POST['trade_no'];//支付宝交易号
            $total_amount=$_POST['total_fee'];//订单总金额
            $trade_statue = $_POST['trade_status'];//支付结果
            // foreach($_GET as $k=>$v){
            //     file_put_contents('./log.txt',file_get_contents('./log.txt')."\n".'校验成功:'.$k.'->'.$v);
            // }
            if ($trade_statue == 'TRADE_FINISHED' || $trade_statue == 'TRADE_SUCCESS') {
                $order=model('admin/OrderModel')->where(['order_code'=>$out_trade_no])->find();
                if($order&&$order->status==1) {//&&($order->price==$total_amount)
                    Db::startTrans();
                    try {
                        $order->status=2;
                        $order->flag=1;
                        $order->trade_no = $trade_no;
                        if ($order->save()) {
                            $res=$this->writeRecord($order->user_id,$total_amount);
                            if ($res) {
                                $serviceDb=model('InstallFeeService','service');
                                $res3=$serviceDb->index($order->id);
                                if($res3){
                                    $user=model('admin/User')->get($order->user_id);
                                    if($user->level!=2){
                                        $user->level=2;
                                        if($user->save()){
                                            Db::commit();
                                            echo 'success';
                                        }else{
                                            throw new Exception();
                                        }
                                    }else{
                                        Db::commit();
                                        echo 'success';
                                    }
                                }else{
                                    throw new Exception();
                                }
                            } else {
                                throw new Exception();
                            }
                        } else {
                            throw new Exception();
                        }
                    } catch (Exception $e) {
                        Db::rollback();
                        echo 'fail';
                    }
                }else{
                    echo 'success';
                }
            }else{
                echo 'fail';
            }
        }else{
            echo 'fail';
        }
    }

    public function writeRecord($user_id,$money)//写入记录
    {
        $date['user_id']=$user_id;
        $date['record_info']='购买商品';
        $date['type']=AccountRecordModel::TYPE_FIVE;
        $date['status']=2;
        $date['money']=$money;
        $res=model('admin/AccountRecordModel')->data($date)->save();
        if($res){
            return true;
        }
        return false;
    }
}
