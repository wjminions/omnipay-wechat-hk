<?php

namespace Omnipay\WechatHk\Message;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\WechatHk\Helper;

/**
 * Class BaseCompletePurchaseRequest
 * @package Omnipay\WechatHk\Message
 */
class BaseCompletePurchaseRequest extends AbstractBaseRequest
{
    public function getData()
    {
        return $this->getRequestParams();
    }


    public function setRequestParams($value)
    {
        $this->setParameter('request_params', $value);
    }


    public function getRequestParams()
    {
        return $this->getParameter('request_params');
    }


    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        $data['is_paid'] = false;

        if ($data['return_code'] == 'SUCCESS' && $data['result_code'] == 'SUCCESS') {
            if (array_key_exists('sign', $data)) {
                $sign = Helper::getSignByMD5($data, $this->getKey());

                if($data['sign'] == $sign){
                    $data['is_paid'] = true;

                    $params = array(
                        "return_code"            => 'SUCCESS',
                        "return_msg"           => 'OK',
                    );

                    echo Helper::arrayToXml($params);
                }
            }
        }

        return $this->response = new BaseCompletePurchaseResponse($this, $data);
    }
}
