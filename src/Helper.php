<?php

namespace Omnipay\WechatHk;

class Helper
{
    public static function getSignByMD5($params, $key)
    {
        //签名步骤一：按字典序排序参数
        ksort($params);
        $string = Helper::ToUrlParams($params);
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=" . $key;
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        return strtoupper($string);
    }

    public static function sendHttpRequest($url, $xml, $cert = '', $key = '')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        if (stripos($url,"https://") !== FALSE) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        } else {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        }

        // cert 与 key 分别属于两个.pem文件
        if (! empty($cert) && ! empty($key)) {
            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLCERT, $cert);
            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLKEY, $key);
        }


        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $result = curl_exec($ch);

        if($result){
            curl_close($ch);

            return $result;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);

            throw new \Exception("curl出错，错误码:$error");
        }
    }

    public static function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val)
        {
            if (is_numeric($val)){
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            }else{
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }

        $xml .= "</xml>";

        return $xml;
    }

    public static function xmlToArray($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);

        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);

        return $values;
    }

    /**
     * 格式化参数格式化成url参数
     */
    public static function ToUrlParams($values)
    {
        $buff = "";
        foreach ($values as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }
}
