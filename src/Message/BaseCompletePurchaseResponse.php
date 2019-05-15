<?php

namespace Omnipay\WechatHk\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Class BaseCompletePurchaseResponse
 * @package Omnipay\WechatHk\Message
 */
class BaseCompletePurchaseResponse extends AbstractResponse
{
    public function isPaid()
    {
        return $this->data['is_paid'];
    }


    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->data['is_paid'];
    }
}
