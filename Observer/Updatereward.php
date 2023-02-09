<?php
namespace Devstree\Reward\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Model\Session;

class Updatereward implements ObserverInterface
{

    /** @var Session */
    protected $session;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerFactory;

    /**
     * Updatewallet constructor.
     * @param Session $customerSession
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     */
    public function __construct(
        Session $customerSession,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\CustomerFactory $customerFactory
    )
    {
        $this->session = $customerSession;
        $this->orderFactory = $orderFactory;
        $this->checkoutSession = $checkoutSession;
        $this->customerFactory = $customerFactory;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $order = $this->orderFactory->create();

            $incrementId = $this->checkoutSession->getLastRealOrderId();
            $order->loadByIncrementId($incrementId);

            $payment = $order->getPayment()->getMethodInstance()->getCode();
            if ($payment == 'rewardpayment') {
                $amount = $order->getGrandtotal();
                $orderid = $order->getIncrementId();

                $customerId = $this->session->getId();
                $customer = $this->customerFactory->create()->load($customerId);
                $time = $order->getCreatedAt();
                $rewardAmount = $customer->getRewardAmount();
                $remainingAmount = $rewardAmount - $amount;

                $customerData = $customer->getDataModel();
                $customerData->setCustomAttribute('reward_amount', $remainingAmount);
                $customer->updateData($customerData);
                $customer->save();
            }
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()));
        }
        return $this;
    }
}
