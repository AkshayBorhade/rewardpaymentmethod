<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Devstree\Reward\Setup;

use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\InstallDataInterface;

class InstallData implements InstallDataInterface
{

    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    /**
     * @var \Magento\Customer\Model\ResourceModel\CustomerFactory
     */
    protected $customerResourceFactory;
    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory,
        \Magento\Framework\App\State $state,
        \Magento\Customer\Model\ResourceModel\CustomerFactory $customerResourceFactory
    )
    {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->customerResourceFactory = $customerResourceFactory;
        $state->setAreaCode('frontend');
    }

    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();
        /** @var $attributeSet AttributeSet */
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        $customerSetup->addAttribute(
            Customer::ENTITY,
            'reward_amount',
            [
                'type' => 'decimal',
                'label' => 'Reward Amount',
                'required' => 0,
                'input' => 'text',
                'default' => 0,
                'system' => 0, // <-- important, otherwise values aren't saved.
                'frontend_class' => 'not-negative-amount',
                'position' => 110
            ]
        );
        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'reward_amount')
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer'],
            ]);

        $attribute->save();
    }
}