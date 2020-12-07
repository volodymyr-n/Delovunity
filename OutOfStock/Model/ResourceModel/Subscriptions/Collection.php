<?php

namespace Delovunity\OutOfStock\Model\ResourceModel\Subscriptions;

use Delovunity\OutOfStock\Model\Subscriptions;
use Delovunity\OutOfStock\Model\ResourceModel\Subscriptions as SubscriptionsResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @inheritdoc
     */
    protected function _construct() //@codingStandardsIgnoreLine
    {
        $this->_init(Subscriptions::class, SubscriptionsResource::class);
    }

    public function getJoinPrudctName($id_user)
    {
        $this->sales_order_table = "main_table";
        $tableProductName = $this->getResource()->getTable('catalog_product_entity_varchar');
        $this->getSelect()->joinLeft(
            ['cpev' => $tableProductName],
            'cpev.entity_id = main_table.id_product AND cpev.attribute_id = 73',
            array('value')
        )->where( 'id_user=?', $id_user);

        return $this;
    }


}
