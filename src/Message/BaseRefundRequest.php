<?php

namespace Omnipay\WechatHk\Message;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\WechatHk\Helper;

/**
 * Class BaseRefundRequest
 *
 * @package Omnipay\WechatHk\Message
 */
class BaseRefundRequest extends AbstractBaseRequest
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
            'out_trade_no',
            'total_fee',
            'refund_fee',
            'out_refund_no',
            'transaction_id',
            'apiclient_cert',
            'apiclient_key'
        );

        $data = array(
            "appid"          => $this->getAppid(),
            "mch_id"         => $this->getMchId(),
            "key"            => $this->getKey(),
            "out_trade_no"   => $this->getOutTradeNo(),
            "total_fee"      => $this->getTotalFee(),
            "refund_fee"     => $this->getRefundFee(),
            "out_refund_no"  => $this->getOutRefundNo(),
            'transaction_id' => $this->getTransactionId(),
            'apiclient_cert' => $this->getApiclientCert(),
            'apiclient_key'  => $this->getApiclientKey(),
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
            "sign_type"      => "MD5",
            "transaction_id" => $this->getTransactionId(),
            "out_trade_no"   => $this->getOutTradeNo(),
            "out_refund_no"  => $this->getOutRefundNo(),
            "total_fee"      => $this->getTotalFee(),
            "refund_fee"     => $this->getRefundFee()
        );

        $params['sign'] = Helper::getSignByMD5($params, $this->getKey());

        $response = Helper::sendHttpRequest($this->getEndpoint('refund'), Helper::arrayToXml($params), $data['apiclient_cert'], $data['apiclient_key']);

        $result = Helper::xmlToArray($response);

        $result['is_paid'] = false;

        if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
            if (array_key_exists('sign', $result)) {
                $sign = Helper::getSignByMD5($result, $this->getKey());

                if ($result['sign'] == $sign) {
                    $result['is_paid'] = true;
                }
            }
        }

        return $this->response = new BaseRefundResponse($this, $result);
    }
}
