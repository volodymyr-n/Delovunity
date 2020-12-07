<?php

namespace Delovunity\OutOfStock\Plugin;
use Magento\Sales\Model\ResourceModel\Order\Grid\Collection as OrderGridCollection;

/**
 * Class AddDataToOrdersGrid
 */
class DataGrid
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * AddDataToOrdersGrid constructor.
     *
     * @param \Psr\Log\LoggerInterface $customLogger
     * @param array $data
     */
    public function __construct(
        \Psr\Log\LoggerInterface $customLogger,
        array $data = []
    ) {
        $this->logger = $customLogger;
    }

    /**
     * @param \Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory $subject
     * @param OrderGridCollection $collection
     * @param $requestName
     * @return mixed
     */
    public function afterGetReport($subject, $collection, $requestName)
    {
        if ($requestName !== 'delovunity_outofstock_subscriptions_listing_data_source') {
            return $collection;
        }
        if ($collection->getMainTable() === $collection->getResource()->getTable('delovunity_outofstock_subscriptions')) {
            try {
                $tableProductSku   = $collection->getResource()->getTable('catalog_product_entity');
                $tableProductName = $collection->getResource()->getTable('catalog_product_entity_varchar');
                $tableCustomer = $collection->getResource()->getTable('customer_grid_flat');
                $tableWebsite = $collection->getResource()->getTable('store_website');
                $collection->getSelect()->joinLeft(
                    ['soat' => $tableProductSku],
                    'soat.entity_id = main_table.id_product',
                    ['sku']
                );
                $collection->getSelect()->joinLeft(
                    ['cpev' => $tableProductName],
                    'cpev.entity_id = main_table.id_product AND cpev.attribute_id = 73',
                    array('value')
                );
                $collection->getSelect()->joinLeft(
                    ['cgf' => $tableCustomer],
                    'cgf.entity_id = main_table.id_user',
                    ['name']
                );
                $collection->getSelect()->joinLeft(
                    ['sw' => $tableWebsite],
                    'sw.website_id = main_table.website',
                    ['website_text' =>'sw.name']
                );
                $collection->getSelect()->columns('id_user');

            } catch (\Zend_Db_Select_Exception $selectException) {
                $this->logger->log(100, $selectException);
            }
        }
        return $collection;
    }
}
