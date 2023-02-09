<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Devstree\Reward\Block\Form;

/**
 * Block for Reward payment method form
 */
class Rewardpayment extends \Magento\OfflinePayments\Block\Form\AbstractInstruction
{
    /**
     * Reward template
     *
     * @var string
     */
    protected $_template = 'Devstree_Reward::form/rewardpayment.phtml';
}
