<?php
/**
 * A Magento 2 module named Dealer4Dealer\SubstituteOrders
 * Copyright (C) 2017 Maikel Martens
 *
 * This file is part of Dealer4Dealer\SubstituteOrders.
 *
 * Dealer4Dealer\SubstituteOrders is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Dealer4Dealer\SubstituteOrders\Setup;

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallSchema implements InstallSchemaInterface
{
    const ORDER_TABLE = "dealer4dealer_order";
    const ORDERITEM_TABLE = "dealer4dealer_orderitem";
    const ORDERADDRESS_TABLE = "dealer4dealer_orderaddress";

    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();
        /* start dealer4dealer_order table */
        $table_dealer4dealer_order = $setup->getConnection()->newTable($setup->getTable(InstallSchema::ORDER_TABLE));

        $table_dealer4dealer_order->addColumn(
            'order_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true,],
            'Entity ID'
        );

        $table_dealer4dealer_order->addColumn(
            'magento_order_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'magento_order_id'
        );

        $table_dealer4dealer_order->addColumn(
            'ext_order_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'ext_order_id'
        );

        $table_dealer4dealer_order->addColumn(
            'po_number',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'po_number'
        );

        $table_dealer4dealer_order->addColumn(
            'magento_customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'magento_customer_id'
        );

        $table_dealer4dealer_order->addColumn(
            'shipping_address_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'shipping_address_id'
        );

        $table_dealer4dealer_order->addColumn(
            'billing_address_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'billing_address_id'
        );

        $table_dealer4dealer_order->addColumn(
            'base_tax_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'base_tax_amount'
        );

        $table_dealer4dealer_order->addColumn(
            'base_discount_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'base_discount_amount'
        );

        $table_dealer4dealer_order->addColumn(
            'base_shipping_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'base_shipping_amount'
        );

        $table_dealer4dealer_order->addColumn(
            'base_subtotal',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'base_subtotal'
        );

        $table_dealer4dealer_order->addColumn(
            'base_grandtotal',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'base_grandtotal'
        );

        $table_dealer4dealer_order->addColumn(
            'shipping_method',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'shipping_method'
        );

        $table_dealer4dealer_order->addColumn(
            'tax_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'tax_amount'
        );

        $table_dealer4dealer_order->addColumn(
            'discount_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'discount_amount'
        );

        $table_dealer4dealer_order->addColumn(
            'shipping_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'shipping_amount'
        );

        $table_dealer4dealer_order->addColumn(
            'subtotal',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'subtotal'
        );

        $table_dealer4dealer_order->addColumn(
            'grandtotal',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'grandtotal'
        );

        $table_dealer4dealer_order->addColumn(
            'order_date',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            [],
            'order_date'
        );

        $table_dealer4dealer_order->addColumn(
            'state',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'state'
        );

        $table_dealer4dealer_order->addColumn(
            'payment_method',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'payment_method'
        );

        $table_dealer4dealer_order->addColumn(
            'additional_data',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'additional_data'
        );
        $setup->getConnection()->createTable($table_dealer4dealer_order);

        /* end dealer4dealer_order table */
        /* start dealer4dealer_orderitem table */
        $table_dealer4dealer_orderitem = $setup->getConnection()->newTable($setup->getTable(InstallSchema::ORDERITEM_TABLE));

        $table_dealer4dealer_orderitem->addColumn(
            'orderitem_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true,],
            'Entity ID'
        );

        $table_dealer4dealer_orderitem->addColumn(
            'order_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'order_id'
        );

        $table_dealer4dealer_orderitem->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'name'
        );

        $table_dealer4dealer_orderitem->addColumn(
            'sku',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'sku'
        );

        $table_dealer4dealer_orderitem->addColumn(
            'base_price',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'base_price'
        );

        $table_dealer4dealer_orderitem->addColumn(
            'price',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'price'
        );

        $table_dealer4dealer_orderitem->addColumn(
            'base_row_total',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'base_row_total'
        );

        $table_dealer4dealer_orderitem->addColumn(
            'row_total',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'row_total'
        );

        $table_dealer4dealer_orderitem->addColumn(
            'base_tax_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'base_tax_amount'
        );

        $table_dealer4dealer_orderitem->addColumn(
            'tax_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'tax_amount'
        );

        $table_dealer4dealer_orderitem->addColumn(
            'qty',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'qty'
        );

        $table_dealer4dealer_orderitem->addColumn(
            'additional_data',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'additional_data'
        );

        $table_dealer4dealer_orderitem->addColumn(
            'base_discount_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'base_discount_amount'
        );

        $table_dealer4dealer_orderitem->addColumn(
            'discount_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['nullable' => false, 'precision' => 12, 'scale' => 4],
            'discount_amount'
        );

        $setup->getConnection()->createTable($table_dealer4dealer_orderitem);
        /* end dealer4dealer_orderitem table */

        /* start dealer4dealer_order_address table */
        $table_dealer4dealer_orderaddress = $setup->getConnection()->newTable($setup->getTable(InstallSchema::ORDERADDRESS_TABLE));

        $table_dealer4dealer_orderaddress->addColumn(
            'orderaddress_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true,],
            'Entity ID'
        );

        $table_dealer4dealer_orderaddress->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'name'
        );

        $table_dealer4dealer_orderaddress->addColumn(
            'company',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'company'
        );

        $table_dealer4dealer_orderaddress->addColumn(
            'street',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'street'
        );

        $table_dealer4dealer_orderaddress->addColumn(
            'postcode',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'postcode'
        );

        $table_dealer4dealer_orderaddress->addColumn(
            'city',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'city'
        );

        $table_dealer4dealer_orderaddress->addColumn(
            'country',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'country'
        );

        $table_dealer4dealer_orderaddress->addColumn(
            'phone',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'phone'
        );

        $table_dealer4dealer_orderaddress->addColumn(
            'fax',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'fax'
        );

        $table_dealer4dealer_orderaddress->addColumn(
            'additional_data',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'additional_data'
        );
        $setup->getConnection()->createTable($table_dealer4dealer_orderaddress);

        /* end dealer4dealer_order_address table */
        $setup->endSetup();
    }
}
