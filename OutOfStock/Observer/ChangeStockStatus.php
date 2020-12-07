<?php

namespace Delovunity\OutOfStock\Observer;

use Magento\Catalog\Model\Product as Product;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use Magento\Framework\Event\ObserverInterface;
use Delovunity\OutOfStock\Model\ResourceModel\Subscriptions\Collection;
use Delovunity\OutOfStock\Helper\Email;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Session\SessionManagerInterface;

class ChangeStockStatus implements ObserverInterface
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var StockItemRepository
     */
    protected $stockItemRepository;


    protected $subscriptions;
    /**
     * @var Email
     */
    protected $send_email;
    protected $helperData;

    /**
     * @var SessionManagerInterface
     */
    protected $session;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfigInterface;
    /**
     * @var Product $product
     */
    protected $product;

    public function __construct(
        StockItemRepository $stockItemRepository,
        Email $send_email,
        Collection $collection,
       ScopeConfigInterface $scopeConfigInterface,
       SessionManagerInterface $session
    )
    {
        $this->stockItemRepository = $stockItemRepository;
        $this->send_email = $send_email;
        $this->collection = $collection;
        $this->session = $session;
        $this->scopeConfigInterface = $scopeConfigInterface;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->product = $observer->getEvent()->getProduct();
        $product_stock=$this->stockItemRepository->get($this->product->getId());
        if($product_stock->getIsInStock()){
            $this->send_email->sendEmail($this);
        }
    }

    /**
     * @return string
     */
    public function getSenderEmail():string
    {
        $email = $this->scopeConfigInterface->getValue('trans_email/ident_support/email',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $email;
    }

    /**
     * @return string
     */
    public function getSenderName():string
    {
        $email = $this->scopeConfigInterface->getValue('trans_email/ident_support/name',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $email;
    }

    /**
     * @return Collection
     */
    public function getReceiverEmails():Collection
    {
        return $this->collection->load($this->product->getId(), 'id_product');
    }

    /**
     * @return string
     */
    public function getProductName():string
    {
        return $this->product->getName();
    }

    /**
     * @return string
     */
    public function getProductUrl():string
    {
        return $this->product->getProductUrl();
    }

    public function getSiteUrl():string
    {
        return $this->product->getStore()->getBaseUrl();
    }

}
