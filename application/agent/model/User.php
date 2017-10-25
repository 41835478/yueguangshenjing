<?php

namespace app\agent\model;

use think\Model;

class User extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = false;
    protected $update = ['updated_at'];

    protected $table = 'ygsj_user';

    protected $token = '';
    private $key = 'huaLove1314';
    protected $rand = 'ILoveYou';

    public function setPwdAttr($value)
    {
        return md5($value);
    }

    public function setToken()
    {
        if (session('token') && session('expire') > time()) {
            $this->token = session('token');
        } else {
            $token = md5(rand(1000, 9999) . $this->rand . uniqid() . time());
            session('token', $token);
            session('expire', time() + 7200);
            $this->token = $token;
        }
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getEncrypt($data)//得到加密值
    {
        $encrypt = $this->encrypt($data, $this->key);
        return $encrypt;
    }

    public function getDecrypt($data)//得到解密
    {
        $decrypt = $this->decrypt($data, $this->key);
        return $decrypt;
    }

    public function encrypt($data, $key)
    {
        $key = md5($key);
        $x = 0;
        $len = strlen($data);
        $l = strlen($key);
        $char = $str = '';
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) {
                $x = 0;
            }
            $char .= $key{$x};
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            $str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
        }
        return base64_encode($str);
    }

    public function decrypt($data, $key)
    {
        $key = md5($key);
        $x = 0;
        $data = base64_decode($data);
        $len = strlen($data);
        $l = strlen($key);
        $char = $str = '';
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) {
                $x = 0;
            }
            $char .= substr($key, $x, 1);
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            } else {
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        return $str;
    }
}
