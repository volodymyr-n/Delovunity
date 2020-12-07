<?php

namespace Delovunity\OutOfStock\Model;

use Delovunity\OutOfStock\Api\Data\SubscriptionsInterface;
use Magento\Framework\Model\AbstractModel;

class Subscriptions  extends AbstractModel implements SubscriptionsInterface
{
    const CACHE_TAG = 'delovunity_outofstock_subscriptions';

    protected $_cacheTag = 'delovunity_outofstock_subscriptions';

    protected $_eventPrefix = 'delovunity_outofstock_subscriptions';

    protected function _construct()
    {
        $this->_init(\Delovunity\OutOfStock\Model\ResourceModel\Subscriptions::class);
    }

    public function setIdProduct(int $id_product)
    {
        $this->setData(SubscriptionsInterface::ID_PRODUCT, $id_product);
        return $this;
    }

    public function getIdProduct()
    {
        return $this->getData(SubscriptionsInterface::ID_PRODUCT);
    }
    public function setEmail(string $email)
    {
        $this->setData(SubscriptionsInterface::EMAIL, $email);
        return $this;
    }

    public function getEmail()
    {
        return $this->getData(SubscriptionsInterface::EMAIL);
    }


    public function setWebSite(int $website)
    {
        $this->setData(SubscriptionsInterface::WEB_SITE, $website);
        return $this;
    }

    public function getWebSite()
    {
        return $this->getData(SubscriptionsInterface::WEB_SITE);
    }

    public function setIdUser(int $id_user)
    {
        $this->setData(SubscriptionsInterface::ID_USER, $id_user);
        return $this;
    }

    public function getIdUser()
    {
        return $this->getData(SubscriptionsInterface::ID_USER);
    }

    public function setCreatedAt(string $createdAt)
    {
        $this->setData(SubscriptionsInterface::CREATED_AT, $createdAt);
        return $this;
    }
    public function getCreatedAt()
    {
        return $this->getData(SubscriptionsInterface::UPDATED_AT);
    }

    public function getUpdatedAt()
    {
        return $this->getData(SubscriptionsInterface::UPDATED_AT);
    }

    public function setUpdatedAt(string $updatedAt)
    {
        $this->setData(SubscriptionsInterface::UPDATED_AT, $updatedAt);
        return $this;
    }
    public function getId()
    {
        return $this->getData(SubscriptionsInterface::ID);
    }

}
