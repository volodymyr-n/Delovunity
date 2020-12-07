<?php

namespace Delovunity\OutOfStock\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Customer\Model\SessionFactory;
use Delovunity\OutOfStock\Model\ResourceModel\Subscriptions\CollectionFactory;
use Delovunity\OutOfStock\Model\SubscriptionsFactory;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\Product;

class SubscriptionFrontEnd extends Template implements ArgumentInterface
{
    /**
     * @var SessionFactory
     */
    private $session;

    /**
     * @var SubscriptionsFactory
     */
    private $subscriptionsFactory;
    /**
     * @var Product
     */
    private $product;

    /**
     * SubscriptionFrontEnd constructor.
     * @param Context $context
     * @param SubscriptionsFactory $subscriptionsFactory
     * @param SessionFactory $session
     * @param array $data
     * @param Product $product
     */
    public function __construct(
        Context $context,
        SubscriptionsFactory $subscriptionsFactory,
        SessionFactory $session,
        Product $product,
        array $data = []
    ){
        parent::__construct($context, $data);
        $this->subscriptionsFactory = $subscriptionsFactory;
        $this->product = $product;
        $this->session=$session->create();
    }

    public function getSubscription(){
        $subscriptions=$this->subscriptionsFactory->create();
        return $subscriptions->getCollection()->getJoinPrudctName($this->getThisCustomerId());
    }


    public function getProductUrl($id): string
    {
        return $this->product->load($id)->getProductUrl();
    }

    private function getThisCustomerId(){
        if ($this->session->isLoggedIn()) {
            return $this->session->getCustomerId();
        }
        return 0;
    }


}
