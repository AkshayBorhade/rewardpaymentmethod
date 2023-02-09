<?php
namespace Devstree\Reward\Setup;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * Vendor setup factory
     *
     * @var VendorSetupFactory
     */
    protected $customerSetupFactory;
    /**
     * Init
     *
     * @param VendorSetupFactory $customerSetupFactory
     */
    public function __construct(CustomerSetupFactory $customerSetupFactory)
    {
       $this->customerSetupFactory = $customerSetupFactory;
    }
    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        if (version_compare($context->getVersion(), '1.0.0', '<')) {
           $customerSetup->updateAttribute(Customer::ENTITY, 'reward_amount', 'validate_rules', '{"not-negative-amount":true}');
        }
        $setup->endSetup();
    }
}