<?php


namespace Delovunity\OutOfStock\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
        $installer = $setup;
        $installer->startSetup();
        if(version_compare($context->getVersion(), '1.2.7', '<')) {
            if (!$installer->tableExists('delovunity_outofstock_subscriptions')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('delovunity_outofstock_subscriptions')
                )
                    ->addColumn(
                        'id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'nullable' => false,
                            'primary'  => true,
                            'unsigned' => true,
                        ],
                        'ID'
                    )
                    ->addColumn(
                        'id_product',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        255,
                        [],
                        'Id Product'
                    )
                    ->addColumn(
                        'email',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'Email'
                    )
                    ->addColumn(
                        'website',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        '64k',
                        [],
                        'Web site'
                    )
                    ->addColumn(
                        'id_user',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        255,
                        [],
                        'Id user'
                    )
                    ->addColumn(
                        'created_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                        'Created At'
                    )->addColumn(
                        'updated_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                        'Updated At')
                    ->setComment('Out of Stock');
                $installer->getConnection()->createTable($table);
                $installer->getConnection()->addIndex(
                    $installer->getTable('delovunity_outofstock_subscriptions'),
                    $setup->getIdxName(
                        $installer->getTable('delovunity_outofstock_subscriptions'),
                        ['id_product', 'email', 'website', 'id_user']
                    ),
                    ['id_product', 'email', 'website', 'id_user', 'email']
                );
            }
        }
        $installer->endSetup();
    }
}
