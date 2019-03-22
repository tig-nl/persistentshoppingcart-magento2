<?php
/**
 *
 *          ..::..
 *     ..::::::::::::..
 *   ::'''''':''::'''''::
 *   ::..  ..:  :  ....::
 *   ::::  :::  :  :   ::
 *   ::::  :::  :  ''' ::
 *   ::::..:::..::.....::
 *     ''::::::::::::''
 *          ''::''
 *
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons License.
 * It is available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to servicedesk@tig.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@tig.nl for more information.
 *
 * @copyright   Copyright (c) Total Internet Group B.V. https://tig.nl/copyright
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 */
namespace TIG\PersistentShoppingCart\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     *
     * @throws \Zend_Db_Exception
     *
     * @codingStandardsIgnoreStart
     * $context is not used and that's correct
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        // @codingStandardsIgnoreEnd
        $setup->startSetup();

        // @codingStandardsIgnoreLine
        $table = $setup->getConnection()->newTable(
            $setup->getTable('shopping_cart_cookie')
        )->addColumn(
            'quote_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            [
                'nullable' => false,
                'primary'  => true,
                'unsigned' => true
            ],
            'Quote ID'
        )->addColumn(
            'token',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            32,
            [
                'nullable' => false
            ],
            'Cookie Token'
        )->addForeignKey(
            $setup->getFkName('shopping_cart_cookie', 'quote_id', 'quote', 'entity_id'),
            'quote_id',
            $setup->getTable('quote'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addIndex(
            $setup->getIdxName('shopping_cart_cookie', ['quote_id']),
            ['quote_id']
        );
    
        // @codingStandardsIgnoreLine
        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}
