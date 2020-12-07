<?php


namespace Delovunity\OutOfStock\Api;

use Magento\Framework\Controller\Result\JsonFactory;
use Delovunity\OutOfStock\Api\Data\SubscriptionsInterface;

/**
 * Interface SubscriptionsManagementInterface
* @api
 */
interface SubscriptionsManagementInterface
{
    /**
     * @param SubscriptionsInterface $subscriptions
     *@return  JsonFactory
     */
    public function save(SubscriptionsInterface $subscriptions);

}
