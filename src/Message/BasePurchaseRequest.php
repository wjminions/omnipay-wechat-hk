<?php

namespace Omnipay\WechatHk\Message;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\WechatHk\Helper;

class BasePurchaseRequest extends AbstractBaseRequest
{

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validateData();

        $data = array(
            "appid"        => $this->getAppid(),
            "mch_id"       => $this->getMchId(),
            "key"          => $this->getKey(),
            "body"         => $this->getBody(),
            "out_trade_no" => $this->getOutTradeNo(),
            "total_fee"    => $this->getTotalFee(),
        );

        return $data;
    }


    private function validateData()
    {
        $this->validate(
            'appid',
            'mch_id',
            'key',
            'body',
            'out_trade_no',
            'total_fee'
        );
    }


    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        $params = array(
            "appid"            => $this->getAppid(),
            "mch_id"           => $this->getMchId(),
            "body"             => $this->getBody(),
            "out_trade_no"     => $this->getOutTradeNo(),
            "total_fee"        => $this->getTotalFee(),
            "nonce_str"        => md5(time()),
            "sign_type"        => 'MD5',
            "spbill_create_ip" => $_SERVER["REMOTE_ADDR"],
            "notify_url"       => $this->getNotifyUrl(),
            "trade_type"       => 'APP',
        );

        $params['sign'] = Helper::getSignByMD5($params, $this->getKey());

        $response = Helper::sendHttpRequest($this->getEndpoint('pay'), Helper::arrayToXml($params));

        $result = Helper::xmlToArray($response);

        if ($result['return_code'] == 'SUCCESS') {
            if (! array_key_exists('sign', $result)) {
                throw new \Exception("签名错误！");
            }

            if ($result['result_code'] != 'SUCCESS') {
                throw new \Exception("結果错误！");
            }

            $sign = Helper::getSignByMD5($result, $this->getKey());

            if ($result['sign'] != $sign) {
                throw new \Exception("签名错误！");
            }
        }

        // 发起APP支付
        $app = array(
            "appid"     => $this->getAppid(),
            "partnerid" => $this->getMchId(),
            "noncestr"  => md5(time()),
            "prepayid"  => $result['prepay_id'],
            "package"   => "Sign=WXPay",
            "timestamp" => time(),
        );

        $app['sign'] = Helper::getSignByMD5($app, $this->getKey());

        $app['return_code'] = $result['return_code'];

        return $this->response = new BasePurchaseResponse($this, json_encode($app));
    }
}
