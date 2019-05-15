<?php

namespace Omnipay\WechatHk\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\WechatHk\Helper;

/**
 * Class AbstractBaseRequest
 * @package Omnipay\WechatHk\Message
 */
abstract class AbstractBaseRequest extends AbstractRequest
{
    protected $sandboxEndpoint = 'https://api.mch.weixin.qq.com/';

    protected $productionEndpoint = 'https://api.mch.weixin.qq.com/';

    protected $methods = array (
        'pay' => 'pay/unifiedorder',
        'refund'   => 'secapi/pay/refund',
        'query' => 'pay/orderquery',
    );

    public function getEndpoint($type)
    {
        if ($this->getEnvironment() == 'production') {
            return $this->productionEndpoint . $this->methods[$type];
        } else {
            return $this->sandboxEndpoint . $this->methods[$type];
        }
    }


    public function setMchId($value)
    {
        return $this->setParameter('mch_id', $value);
    }

    public function getMchId()
    {
        return $this->getParameter('mch_id');
    }


    public function setKey($value)
    {
        return $this->setParameter('key', $value);
    }

    public function getKey()
    {
        return $this->getParameter('key');
    }


    public function setApiclientCert($value)
    {
        return $this->setParameter('apiclient_cert', $value);
    }

    public function getApiclientCert()
    {
        return $this->getParameter('apiclient_cert');
    }


    public function setApiclientKey($value)
    {
        return $this->setParameter('apiclient_key', $value);
    }

    public function getApiclientKey()
    {
        return $this->getParameter('apiclient_key');
    }


    public function setEnvironment($value)
    {
        return $this->setParameter('environment', $value);
    }

    public function getEnvironment()
    {
        return $this->getParameter('environment');
    }


    public function setAppid($value)
    {
        return $this->setParameter('appid', $value);
    }

    public function getAppid()
    {
        return $this->getParameter('appid');
    }


    public function setNotifyUrl($value)
    {
        return $this->setParameter('notify_url', $value);
    }

    public function getNotifyUrl()
    {
        return $this->getParameter('notify_url');
    }


    public function setOutTradeNo($value)
    {
        return $this->setParameter('out_trade_no', $value);
    }

    public function getOutTradeNo()
    {
        return $this->getParameter('out_trade_no');
    }


    public function setBody($value)
    {
        return $this->setParameter('body', $value);
    }

    public function getBody()
    {
        return $this->getParameter('body');
    }


    public function setTotalFee($value)
    {
        return $this->setParameter('total_fee', $value);
    }

    public function getTotalFee()
    {
        return $this->getParameter('total_fee');
    }


    public function setRefundFee($value)
    {
        return $this->setParameter('refund_fee', $value);
    }

    public function getRefundFee()
    {
        return $this->getParameter('refund_fee');
    }


    public function setOutRefundNo($value)
    {
        return $this->setParameter('out_refund_no', $value);
    }

    public function getOutRefundNo()
    {
        return $this->getParameter('out_refund_no');
    }


    public function setTransactionId($value)
    {
        return $this->setParameter('transaction_id', $value);
    }

    public function getTransactionId()
    {
        return $this->getParameter('transaction_id');
    }
}
