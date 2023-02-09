<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Devstree\Reward\Model;

class Rewardpayment extends \Magento\Payment\Model\Method\AbstractMethod
{
    const PAYMENT_METHOD_REWARDPAYMENT_CODE = 'rewardpayment';

    /**
     * Payment method code
     *
     * @var string
     */
    protected $_code = self::PAYMENT_METHOD_REWARDPAYMENT_CODE;

    /**
     * Reward payment block paths
     *
     * @var string
     */
    protected $_formBlockType = \Devstree\Reward\Block\Form\Rewardpayment::class;

    /**
     * Info instructions block path
     *
     * @var string
     */
    protected $_infoBlockType = \Magento\Payment\Block\Info\Instructions::class;

    /**
     * Availability option
     *
     * @var bool
     */
    protected $_isOffline = true;

    /**
     * Get instructions text from config
     *
     * @return string
     */
    public function getInstructions()
    {
        return trim($this->getConfigData('instructions'));
    }
}
