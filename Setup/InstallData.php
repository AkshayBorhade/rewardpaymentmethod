<?php
namespace Devstree\Reward\Setup;

use Magento\Eav\Model\Config;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface 
{
 private $eavSetupFactory;

 public function __construct(
 EavSetupFactory $eavSetupFactory,
 Config $eavConfig
 ) 
 {
 $this->eavSetupFactory = $eavSetupFactory;
 $this->eavConfig = $eavConfig;
 }

 public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context) 
 {
 $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

 

//creating a custom text field programmatically

 $eavSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'reward_amount', [
    'label' => 'Reward Amount',
    'system' => 0,
    'position' => 700,
    'sort_order' => 700,
    'visible' => true,
    'note' => '',
    'type' => 'int',
    'input' => 'text',
    ]
 );

    $this->getEavConfig()->getAttribute('customer', 'reward_amount')
          ->setData('is_user_defined', 1)
          ->setData('is_required', 0)
          ->setData('default_value', '')
          ->setData('used_in_forms', ['adminhtml_customer'])->save();
}
 
 public function getEavConfig() {
 return $this->eavConfig;
 }
}
