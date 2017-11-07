<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3
 * Time: 11:47
 */
namespace app\home\service;
use think\Exception;
use think\Model;

class AliAuthLoginService extends Model
{
    //应用ID
    public $appId;
    // 表单提交字符集编码
    public $postCharset = "UTF-8";
    private $fileCharset = "UTF-8";
    //api版本
    public $apiVersion = "1.0";
    //返回数据格式
    public $format = "json";
    //签名类型
    public $signType = "RSA";
    protected $alipaySdkVersion = "alipay-sdk-php-20161101";
    //加密密钥和类型
    public $encryptKey;
    public $encryptType = "AES";
    //私钥文件路径
    public $rsaPrivateKeyFilePath;
    //私钥值
    public $rsaPrivateKey;
    //网关
    public $gatewayUrl = "https://openapi.alipay.com/gateway.do";
    //使用文件读取文件格式，请只传递该值
    public $alipayPublicKey = null;
    private $RESPONSE_SUFFIX = "_response";
    private $ERROR_RESPONSE = "error_response";
    //使用读取字符串格式，请只传递该值
    public $alipayrsaPublicKey;
    private $SIGN_NODE_NAME = "sign";
    private $apiMethodName="alipay.user.userinfo.share";

    public function execute($request, $authToken = null, $appInfoAuthtoken = null)//发起执行
    {
        $this->setupCharsets($request);
        //		//  如果两者编码不一致，会出现签名验签或者乱码
        if (strcasecmp($this->fileCharset, $this->postCharset)) {
            // writeLog("本地文件字符集编码与表单提交编码不一致，请务必设置成一样，属性名分别为postCharset!");
            throw new Exception("文件编码：[" . $this->fileCharset . "] 与表单提交编码：[" . $this->postCharset . "]两者不一致!");
        }
        $iv = $this->apiVersion;
        //组装系统参数
        $sysParams["app_id"] = $this->appId;
        $sysParams["version"] = $iv;
        $sysParams["format"] = $this->format;
        $sysParams["sign_type"] = $this->signType;
        $sysParams["method"] = $this->apiMethodName;
        $sysParams["timestamp"] = date("Y-m-d H:i:s");
        $sysParams["auth_token"] = $authToken;
        $sysParams["alipay_sdk"] = $this->alipaySdkVersion;
        $sysParams["terminal_type"] = $request->getTerminalType();
        $sysParams["terminal_info"] = $request->getTerminalInfo();
        $sysParams["prod_code"] = $request->getProdCode();
        $sysParams["notify_url"] = $request->getNotifyUrl();
        $sysParams["charset"] = $this->postCharset;
        $sysParams["app_auth_token"] = $appInfoAuthtoken;
        //获取业务参数
        $apiParams = $request->getApiParas();
        if (method_exists($request,"getNeedEncrypt") &&$request->getNeedEncrypt()){
            $sysParams["encrypt_type"] = $this->encryptType;
            if ($this->checkEmpty($apiParams['biz_content'])) {
                throw new Exception(" api request Fail! The reason : encrypt request is not supperted!");
            }
            if ($this->checkEmpty($this->encryptKey) || $this->checkEmpty($this->encryptType)) {
                throw new Exception(" encryptType and encryptKey must not null! ");
            }
            if ("AES" != $this->encryptType) {
                throw new Exception("加密类型只支持AES");
            }
            // 执行加密
            $enCryptContent = $this->encrypt($apiParams['biz_content'], $this->encryptKey);
            $apiParams['biz_content'] = $enCryptContent;
        }
        //签名
        $sysParams["sign"] = $this->generateSign(array_merge($apiParams, $sysParams), $this->signType);
        //系统参数放入GET请求串
        $requestUrl = $this->gatewayUrl . "?";
        foreach ($sysParams as $sysParamKey => $sysParamValue) {
            $requestUrl .= "$sysParamKey=" . urlencode($this->characet($sysParamValue, $this->postCharset)) . "&";
        }
        $requestUrl = substr($requestUrl, 0, -1);
        //发起HTTP请求
        try {
            $resp = $this->curl($requestUrl, $apiParams);
        } catch (Exception $e) {
            return false;
        }
        //解析AOP返回结果
        $respWellFormed = false;
        // 将返回结果转换本地文件编码
        $r = iconv($this->postCharset, $this->fileCharset . "//IGNORE", $resp);
        $signData = null;
        if ("json" == $this->format) {
            $respObject = json_decode($r);
            if (null !== $respObject) {
                $respWellFormed = true;
                $signData = $this->parserJSONSignData($resp, $respObject);
            }
        }

        //返回的HTTP文本不是标准JSON或者XML，记下错误日志
        if (false === $respWellFormed) {
            return false;
        }

        // 验签
        $this->checkResponseSign($signData, $resp, $respObject);
        // 解密
        if (method_exists($request,"getNeedEncrypt") &&$request->getNeedEncrypt()){
            if ("json" == $this->format) {
                $resp = $this->encryptJSONSignSource($resp);
                // 将返回结果转换本地文件编码
                $r = iconv($this->postCharset, $this->fileCharset . "//IGNORE", $resp);
                $respObject = json_decode($r);
            }
        }
        return $respObject;
    }

    // 获取加密内容
    private function encryptJSONSignSource($responseContent) {
        $parsetItem = $this->parserEncryptJSONSignSource($responseContent);
        $bodyIndexContent = substr($responseContent, 0, $parsetItem['startIndex']);
        $bodyEndContent = substr($responseContent, $parsetItem['endIndex'], strlen($responseContent) + 1 - $parsetItem['endIndex']);
        $bizContent = $this->decrypt($parsetItem['encryptContent'], $this->encryptKey);
        return $bodyIndexContent . $bizContent . $bodyEndContent;
    }

    private function parserEncryptJSONSignSource($responseContent) {
        $apiName = $this->apiMethodName;
        $rootNodeName = str_replace(".", "_", $apiName) . $this->RESPONSE_SUFFIX;
        $rootIndex = strpos($responseContent, $rootNodeName);
        $errorIndex = strpos($responseContent, $this->ERROR_RESPONSE);
        if ($rootIndex > 0) {
            return $this->parserEncryptJSONItem($responseContent, $rootNodeName, $rootIndex);
        } else if ($errorIndex > 0) {
            return $this->parserEncryptJSONItem($responseContent, $this->ERROR_RESPONSE, $errorIndex);
        } else {
            return null;
        }
    }

    private function parserEncryptJSONItem($responseContent, $nodeName, $nodeIndex) {
        $signDataStartIndex = $nodeIndex + strlen($nodeName) + 2;
        $signIndex = strpos($responseContent, "\"" . $this->SIGN_NODE_NAME . "\"");
        // 签名前-逗号
        $signDataEndIndex = $signIndex - 1;
        if ($signDataEndIndex < 0) {
            $signDataEndIndex = strlen($responseContent)-1 ;
        }
        $indexLen = $signDataEndIndex - $signDataStartIndex;
        $encContent = substr($responseContent, $signDataStartIndex+1, $indexLen-2);
        $encryptParseItem['encryptContent'] = $encContent;
        $encryptParseItem['startIndex'] = $signDataStartIndex;
        $encryptParseItem['endIndex'] = $signDataEndIndex;
        return $encryptParseItem;
    }

    function parserJSONSignData($responseContent, $responseJSON) {
        $signData['sign'] = $responseJSON->sign;;
        $signData['signSourceData'] = $this->parserJSONSignSource($responseContent);
        return $signData;
    }

    function parserJSONSignSource($responseContent) {
        $apiName = $this->apiMethodName;
        $rootNodeName = str_replace(".", "_", $apiName) . $this->RESPONSE_SUFFIX;
        $rootIndex = strpos($responseContent, $rootNodeName);
        $errorIndex = strpos($responseContent, $this->ERROR_RESPONSE);
        if ($rootIndex > 0) {
            return $this->parserJSONSource($responseContent, $rootNodeName, $rootIndex);
        } else if ($errorIndex > 0) {
            return $this->parserJSONSource($responseContent, $this->ERROR_RESPONSE, $errorIndex);
        } else {
            return null;
        }
    }

    function parserJSONSource($responseContent, $nodeName, $nodeIndex) {
        $signDataStartIndex = $nodeIndex + strlen($nodeName) + 2;
        $signIndex = strpos($responseContent, "\"" . $this->SIGN_NODE_NAME . "\"");
        // 签名前-逗号
        $signDataEndIndex = $signIndex - 1;
        $indexLen = $signDataEndIndex - $signDataStartIndex;
        if ($indexLen < 0) {
            return null;
        }
        return substr($responseContent, $signDataStartIndex, $indexLen);
    }

    /**
     * 验签
     * @param $signData
     * @param $resp
     * @param $respObject
     * @throws Exception
     */
    public function checkResponseSign($signData, $resp, $respObject) {
        if (!$this->checkEmpty($this->alipayPublicKey) || !$this->checkEmpty($this->alipayrsaPublicKey)) {
            if ($signData == null || $this->checkEmpty($signData['sign']) || $this->checkEmpty($signData['signSourceData'])) {
                throw new Exception(" check sign Fail! The reason : signData is Empty");
            }
            // 获取结果sub_code
            $responseSubCode = $this->parserResponseSubCode($resp, $respObject, $this->format);
            if (!$this->checkEmpty($responseSubCode) || ($this->checkEmpty($responseSubCode) && !$this->checkEmpty($signData['sign']))) {
                $checkResult = $this->verify($signData['signSourceData'], $signData['sign'], $this->alipayPublicKey, $this->signType);
                if (!$checkResult) {
                    if (strpos($signData['signSourceData'], "\\/") > 0) {
                        $signData['signSourceData'] = str_replace("\\/", "/", $signData['signSourceData']);
                        $checkResult = $this->verify($signData['signSourceData'], $signData['sign'], $this->alipayPublicKey, $this->signType);
                        if (!$checkResult) {
                            throw new Exception("check sign Fail! [sign=" . $signData['sign'] . ", signSourceData=" . $signData['signSourceData'] . "]");
                        }
                    } else {
                        throw new Exception("check sign Fail! [sign=" . $signData['sign'] . ", signSourceData=" . $signData['signSourceData'] . "]");
                    }
                }
            }
        }
    }

    function verify($data, $sign, $rsaPublicKeyFilePath, $signType = 'RSA') {
        if($this->checkEmpty($this->alipayPublicKey)){
            $pubKey= $this->alipayrsaPublicKey;
            $res = "-----BEGIN PUBLIC KEY-----\n" .
                wordwrap($pubKey, 64, "\n", true) .
                "\n-----END PUBLIC KEY-----";
        }else {
            //读取公钥文件
            $pubKey = file_get_contents($rsaPublicKeyFilePath);
            //转换为openssl格式密钥
            $res = openssl_get_publickey($pubKey);
        }
        ($res) or die('支付宝RSA公钥错误。请检查公钥文件格式是否正确');
        //调用openssl内置方法验签，返回bool值
        if ("RSA2" == $signType) {
            $result = (bool)openssl_verify($data, base64_decode($sign), $res, OPENSSL_ALGO_SHA256);
        } else {
            $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        }
        if(!$this->checkEmpty($this->alipayPublicKey)) {
            //释放资源
            openssl_free_key($res);
        }
        return $result;
    }

    function parserResponseSubCode($responseContent, $respObject, $format) {
        if ("json" == $format) {
            $apiName = $this->apiMethodName;
            $rootNodeName = str_replace(".", "_", $apiName) . $this->RESPONSE_SUFFIX;
            $errorNodeName = $this->ERROR_RESPONSE;
            $rootIndex = strpos($responseContent, $rootNodeName);
            $errorIndex = strpos($responseContent, $errorNodeName);
            if ($rootIndex > 0) {
                // 内部节点对象
                $rInnerObject = $respObject->$rootNodeName;
            } elseif ($errorIndex > 0) {
                $rInnerObject = $respObject->$errorNodeName;
            } else {
                return null;
            }
            // 存在属性则返回对应值
            if (isset($rInnerObject->sub_code)) {
                return $rInnerObject->sub_code;
            } else {
                return null;
            }
        } elseif ("xml" == $format) {
            // xml格式sub_code在同一层级
            return $respObject->sub_code;

        }
    }

    protected function curl($url, $postFields = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $postBodyString = "";
        $encodeArray = Array();
        $postMultipart = false;
        if (is_array($postFields) && 0 < count($postFields)) {
            foreach ($postFields as $k => $v) {
                if ("@" != substr($v, 0, 1)) //判断是不是文件上传
                {
                    $postBodyString .= "$k=" . urlencode($this->characet($v, $this->postCharset)) . "&";
                    $encodeArray[$k] = $this->characet($v, $this->postCharset);
                } else //文件上传用multipart/form-data，否则用www-form-urlencoded
                {
                    $postMultipart = true;
                    $encodeArray[$k] = new \CURLFile(substr($v, 1));
                }
            }
            unset ($k, $v);
            curl_setopt($ch, CURLOPT_POST, true);
            if ($postMultipart) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $encodeArray);
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString, 0, -1));
            }
        }
        if ($postMultipart) {
            $headers = array('content-type: multipart/form-data;charset=' . $this->postCharset . ';boundary=' . $this->getMillisecond());
        } else {
            $headers = array('content-type: application/x-www-form-urlencoded;charset=' . $this->postCharset);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $reponse = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch), 0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new Exception($reponse, $httpStatusCode);
            }
        }
        curl_close($ch);
        return $reponse;
    }

    protected function getMillisecond() {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }

    public function generateSign($params, $signType = "RSA") {
        return $this->sign($this->getSignContent($params), $signType);
    }

    protected function sign($data, $signType = "RSA") {
        if($this->checkEmpty($this->rsaPrivateKeyFilePath)){
            $priKey=$this->rsaPrivateKey;
            $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
                wordwrap($priKey, 64, "\n", true) .
                "\n-----END RSA PRIVATE KEY-----";
        }else {
            $priKey = file_get_contents($this->rsaPrivateKeyFilePath);
            $res = openssl_get_privatekey($priKey);
        }

        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');

        if ("RSA2" == $signType) {
            openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
        } else {
            openssl_sign($data, $sign, $res);
        }

        if(!$this->checkEmpty($this->rsaPrivateKeyFilePath)){
            openssl_free_key($res);
        }
        $sign = base64_encode($sign);
        return $sign;
    }

    public function getSignContent($params) {
        ksort($params);

        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {

                // 转换成目标字符集
                $v = $this->characet($v, $this->postCharset);

                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }

        unset ($k, $v);
        return $stringToBeSigned;
    }

    /**
     * 转换字符集编码
     * @param $data
     * @param $targetCharset
     * @return string
     */
    function characet($data, $targetCharset) {
        if (!empty($data)) {
            $fileType = $this->fileCharset;
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
                //				$data = iconv($fileType, $targetCharset.'//IGNORE', $data);
            }
        }
        return $data;
    }

    private function setupCharsets($request) {
        if ($this->checkEmpty($this->postCharset)) {
            $this->postCharset = 'UTF-8';
        }
        $str = preg_match('/[\x80-\xff]/', $this->appId) ? $this->appId : print_r($request, true);
        $this->fileCharset = mb_detect_encoding($str, "UTF-8, GBK") == 'UTF-8' ? 'UTF-8' : 'GBK';
    }

    /**
     * 校验$value是否非空
     *  if not set ,return true;
     *    if is null , return true;
     **/
    protected function checkEmpty($value) {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;
        return false;
    }

    /**
     * 加密方法
     * @param string $str
     * @return string
     */
    public function encrypt($str,$screct_key){
        //AES, 128 模式加密数据 CBC
        $screct_key = base64_decode($screct_key);
        $str = trim($str);
        $str = $this->addPKCS7Padding($str);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_CBC),1);
        $encrypt_str =  mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $screct_key, $str, MCRYPT_MODE_CBC);
        return base64_encode($encrypt_str);
    }

    /**
     * 解密方法
     * @param string $str
     * @return string
     */
    public function decrypt($str,$screct_key){
        //AES, 128 模式加密数据 CBC
        $str = base64_decode($str);
        $screct_key = base64_decode($screct_key);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_CBC),1);
        $encrypt_str =  mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $screct_key, $str, MCRYPT_MODE_CBC);
        $encrypt_str = trim($encrypt_str);

        $encrypt_str = $this->stripPKSC7Padding($encrypt_str);
        return $encrypt_str;

    }

    /**
     * 填充算法
     * @param string $source
     * @return string
     */
    public function addPKCS7Padding($source){
        $source = trim($source);
        $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);

        $pad = $block - (strlen($source) % $block);
        if ($pad <= $block) {
            $char = chr($pad);
            $source .= str_repeat($char, $pad);
        }
        return $source;
    }
    /**
     * 移去填充算法
     * @param string $source
     * @return string
     */
    public function stripPKSC7Padding($source){
        $source = trim($source);
        $char = substr($source, -1);
        $num = ord($char);
        if($num==62)return $source;
        $source = substr($source,0,-$num);
        return $source;
    }
}