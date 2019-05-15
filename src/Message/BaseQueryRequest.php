<?php

namespace Omnipay\WechatHk\Message;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\WechatHk\Helper;

/**
 * Class BaseQueryRequest
 *
 * @package Omnipay\WechatHk\Message
 */
class BaseQueryRequest extends AbstractBaseRequest
{

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate(
            'appid',
            'mch_id',
            'key',
            'out_trade_no'
        );

        $data = array(
            "appid"          => $this->getAppid(),
            "mch_id"         => $this->getMchId(),
            "key"            => $this->getKey(),
            "out_trade_no"   => $this->getOutTradeNo(),
        );

        return $data;
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
            "appid"          => $this->getAppid(),
            "mch_id"         => $this->getMchId(),
            "nonce_str"      => md5(time()),
            "out_trade_no"   => $this->getOutTradeNo()
        );

        $params['sign'] = Helper::getSignByMD5($params, $this->getKey());

        $response = Helper::sendHttpRequest($this->getEndpoint('query'), Helper::arrayToXml($params));

        $result = Helper::xmlToArray($response);

        $result['is_paid'] = false;

        if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS' && $result['trade_state'] == 'SUCCESS') {
            if (array_key_exists('sign', $result)) {
                $sign = Helper::getSignByMD5($result, $this->getKey());

                if ($result['sign'] == $sign) {
                    $result['is_paid'] = true;
                }
            }
        }

        return $result;
    }
}
