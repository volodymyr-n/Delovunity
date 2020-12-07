<?php
namespace Delovunity\OutOfStock\Service;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Delovunity\OutOfStock\Api\Data\SubscriptionsInterface;
use Delovunity\OutOfStock\Api\SubscriptionsManagementInterface;
use Delovunity\OutOfStock\Model\SubscriptionsFactory;
use Delovunity\OutOfStock\Model\ResourceModel\Subscriptions as SubscriptionsResource;
use Magento\Store\Model\StoreManagerInterface;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;

class SubscriptionsRepository implements SubscriptionsManagementInterface
{
    /**
     * @var array
     */
    private $registry = [];

    /**
     * @var SubscriptionsFactory
     */
    private $subscriptionsFactory;

    /**
     * @var SubscriptionsResource
     */
    private $resource;

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $session;

    /**
     * @var StoreManagerInterface
     */
    private $store_manager;
    /**
     * @var StoreManagerInterface
     */
    private $stockItemRepository;

    public function __construct(
        SubscriptionsFactory $subscriptionsFactory,
    SubscriptionsResource $resource,
        \Magento\Customer\Model\Session $session,
        StoreManagerInterface $store_manager,
        StockItemRepository $stockItemRepository
)
    {
        $this->subscriptionsFactory = $subscriptionsFactory;
        $this->resource = $resource;
        $this->stockItemRepository = $stockItemRepository;
        $this->session = $session;
        $this->store_manager = $store_manager;
    }

/**
 * @param SubscriptionsInterface
 * @return string
 */
    public function get(int $id)
    {
        if (!array_key_exists($id, $this->registry)) {
            $subscriptions = $this->subscriptionsFactory->create();
            $this->resource->load($subscriptions, $id);
            if (!$subscriptions->getId()) {
                throw new NoSuchEntityException(__('Requested subscriptions does not exist'));
            }
            $this->registry[$id] = $subscriptions;
        }
        return $this->registry[$id];
    }

    public function validation(SubscriptionsInterface $subscriptions){
        $validation='';
        if (filter_var($subscriptions->getEmail(), FILTER_VALIDATE_EMAIL)):
            $subscriptions_db = $this->subscriptionsFactory->create()->load($subscriptions->getEmail(), 'email');
            $collection = $subscriptions_db->getCollection()->addFilter('email', $subscriptions->getEmail())->addFilter('id_product', $subscriptions->getIdProduct());
            if (!count($collection)):
                $product=$this->stockItemRepository->get($subscriptions->getIdProduct());
                if ($product->getItemId()):
                    if ($product->getIsInStock()):
                        $validation = __('The product is in stock')->getText();
                    endif;
                else:
                    $validation = __('There is no such product')->getText();
                endif;
            else:
                $validation = __('You have already subscribed')->getText();
            endif;
        else:
            $validation = __('Invalid email format')->getText();
        endif;
        return $validation;
}

/**
 *@return string
 */
    public function save(SubscriptionsInterface $subscriptions)
    {
        $subscriptions->setIdUser((int)$this->session->getCustomerId());
        $subscriptions->setWebSite($this->store_manager->getDefaultStoreView()->getWebsiteId());
        $validation = $this->validation($subscriptions);
      if(!$validation):
        try {
            $this->resource->save($subscriptions);
            $this->registry[$subscriptions->getId()] = $this->get($subscriptions->getId());
        } catch (\Exception $exception) {
            throw new StateException(__('Unable to save subscriptions #%1', $subscriptions->getId()));
        }
      endif;
      $massager = ($subscriptions->getId() && $this->registry[$subscriptions->getId()]) ? 'Successes save' : $validation;
        return $massager;
    }
}
