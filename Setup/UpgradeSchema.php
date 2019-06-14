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
use Magento\Framework\Setup\SetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), "1.0.1", "<")) {
            $this->onePointZeroPointOne($installer);
        }

        if (version_compare($context->getVersion(), "1.0.2", "<")) {
            $this->onePointZeroPointTwo($installer);
        }

        if (version_compare($context->getVersion(), "1.0.3", "<")) {
            $this->onePointZeroPointThree($installer);
        }

        if (version_compare($context->getVersion(), "1.0.4", "<")) {
            $this->invoiceTables($installer);
        }

        if (version_compare($context->getVersion(), "1.0.5", "<")) {
            $this->shipmentTables($installer);
        }

        if (version_compare($context->getVersion(), "1.0.6", "<")) {
            $this->multipleOrdersOnInvoice($installer);
        }

        if (version_compare($context->getVersion(), "1.0.7", "<")) {
            $this->seperateOrderInvoiceTable($installer);
        }

        if (version_compare($context->getVersion(), "1.1.4", "<")) {
            $this->addIndexToFields($installer);
        }

        if (version_compare($context->getVersion(), "2.0.0", "<")) {
            $this->attachmentTables($installer);
        }
        
        if (version_compare($context->getVersion(), "2.0.1", "<")) {
            $this->addExternalCustomerField($installer);
        }

        if (version_compare($context->getVersion(), "2.0.3", "<")) {
            $this->addShippingMethodField($installer);
        }

        $installer->endSetup();
    }

    public function onePointZeroPointOne($installer)
    {
        $table = $installer->getTable(InstallSchema::ORDER_TABLE);

        $columns = [
            'updated_at' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                'nullable' => true,
                'comment' => "Last updated date"
            ],
            'magento_increment_id' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'Magento Increment ID',
            ],
        ];
        $connection = $installer->getConnection();

        foreach ($columns as $name => $definition) {
            $connection->addColumn($table, $name, $definition);
        }
    }

    public function onePointZeroPointTwo($installer)
    {
        $table = $installer->getTable(InstallSchema::ORDERADDRESS_TABLE);
        $connection = $installer->getConnection();

        $dropColumns = [
            "name",
            "phone",
        ];

        foreach ($dropColumns as $column) {
            $connection->dropColumn($table, $column);
        }

        $addColumns = [
            "prefix" => [
                "type" => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                "nullable" => true,
                'comment' => "Prefix"
            ],
            "firstname" => [
                "type" => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                "nullable" => true,
                'comment' => "Firstname"
            ],
            "middlename" => [
                "type" => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                "nullable" => true,
                'comment' => "Middlename"
            ],
            "lastname" => [
                "type" => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                "nullable" => true,
                'comment' => "Lastname"
            ],
            "suffix" => [
                "type" => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                "nullable" => true,
                'comment' => "Suffix"
            ],
        ];

        foreach ($addColumns as $column => $definition) {
            $connection->addColumn($table, $column, $definition);
        }
    }

    public function onePointZeroPointThree($installer)
    {
        $table = $installer->getTable(InstallSchema::ORDERADDRESS_TABLE);

        $connection = $installer->getConnection();
        $addColumns = [
            "order_id" => [
                "type" => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                "nullable" => true,
                'comment' => "Order Id"
            ],
        ];
        foreach ($addColumns as $column => $definition) {
            $connection->addColumn($table, $column, $definition);
        }
    }

    public function invoiceTables($installer)
    {
        $table_dealer4dealer_invoice = $installer->getConnection()->newTable($installer->getTable('dealer4dealer_invoice'));

        $table_dealer4dealer_invoice->addColumn(
            'invoice_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true,],
            'Entity ID'
        )->addColumn(
            'magento_invoice_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'magento_invoice_id'
        )->addColumn(
            'order_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'order_id'
        )->addColumn(
            'ext_invoice_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'ext_invoice_id'
        )->addColumn(
            'po_number',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'po_number'
        )->addColumn(
            'magento_customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'magento_customer_id'
        )->addColumn(
            'shipping_address_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'shipping_address_id'
        )->addColumn(
            'billing_address_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'billing_address_id'
        )->addColumn(
            'base_tax_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'base_tax_amount'
        )->addColumn(
            'base_discount_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'base_discount_amount'
        )->addColumn(
            'base_shipping_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'base_shipping_amount'
        )->addColumn(
            'base_subtotal',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'base_subtotal'
        )->addColumn(
            'base_grandtotal',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'base_grandtotal'
        )->addColumn(
            'tax_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'tax_amount'
        )->addColumn(
            'discount_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'discount_amount'
        )->addColumn(
            'shipping_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'shipping_amount'
        )->addColumn(
            'subtotal',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'subtotal'
        )->addColumn(
            'grandtotal',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'grandtotal'
        )->addColumn(
            'invoice_date',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'invoice_date'
        )->addColumn(
            'updated_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'updated_at'
        )->addColumn(
            'state',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'state'
        )->addColumn(
            'magento_increment_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'magento_increment_id'
        )->addColumn(
            'additional_data',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'additional_data'
        );

        $installer->getConnection()->createTable($table_dealer4dealer_invoice);


        /* items */
        $table_dealer4dealer_invoice_item = $installer->getConnection()->newTable($installer->getTable('dealer4dealer_invoice_item'));

        $table_dealer4dealer_invoice_item->addColumn(
            'invoiceitem_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true,],
            'Entity ID'
        )->addColumn(
            'invoice_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'invoice_id'
        )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'name'
        )->addColumn(
            'sku',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'sku'
        )->addColumn(
            'base_price',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'base_price'
        )->addColumn(
            'price',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'price'
        )->addColumn(
            'base_row_total',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'base_row_total'
        )->addColumn(
            'row_total',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'row_total'
        )->addColumn(
            'base_tax_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'base_tax_amount'
        )->addColumn(
            'tax_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'tax_amount'
        )->addColumn(
            'qty',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'qty'
        )->addColumn(
            'additional_data',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'additional_data'
        )->addColumn(
            'base_discount_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'base_discount_amount'
        )->addColumn(
            'discount_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'discount_amount'
        );

        $installer->getConnection()->createTable($table_dealer4dealer_invoice_item);
    }

    public function shipmentTables($installer)
    {

        $table_dealer4dealer_shipment = $installer->getConnection()->newTable($installer->getTable('dealer4dealer_shipment'));


        $table_dealer4dealer_shipment->addColumn(
            'shipment_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true,],
            'Entity ID'
        )->addColumn(
            'ext_shipment_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'ext_shipment_id'
        )->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'customer_id'
        )->addColumn(
            'invoice_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'invoice_id'
        )->addColumn(
            'order_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'order_id'
        )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'name'
        )->addColumn(
            'shipment_status',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'shipment_status'
        )->addColumn(
            'shipping_address_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'shipping_address_id'
        )->addColumn(
            'billing_address_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'billing_address_id'
        )->addColumn(
            'increment_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'increment_id'
        )->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            [],
            'created_at'
        )->addColumn(
            'updated_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            [],
            'updated_at'
        )->addColumn(
            'tracking',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'tracking'
        )->addColumn(
            'additional_data',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'additional_data'
        );

        $installer->getConnection()->createTable($table_dealer4dealer_shipment);


        /* Item */

        $table_dealer4dealer_shipmentitem = $installer->getConnection()->newTable($installer->getTable('dealer4dealer_shipmentitem'));

        $table_dealer4dealer_shipmentitem->addColumn(
            'shipmentitem_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true,],
            'Entity ID'
        )->addColumn(
            'shipment_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'shipment_id'
        )->addColumn(
            'row_total',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'row_total'
        )->addColumn(
            'price',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'price'
        )->addColumn(
            'weight',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'weight'
        )->addColumn(
            'qty',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            null,
            ['precision' => 12, 'scale' => 4],
            'qty'
        )->addColumn(
            'sku',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'sku'
        )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'name'
        )->addColumn(
            'description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'description'
        )->addColumn(
            'additional_data',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'additional_data'
        );


        $installer->getConnection()->createTable($table_dealer4dealer_shipmentitem);
    }

    public function multipleOrdersOnInvoice($installer)
    {
        $orderTable = $installer->getTable(InstallSchema::ORDER_TABLE);
        $invoiceTable = $installer->getTable('dealer4dealer_invoice');
        $invoiceItemTable = $installer->getTable('dealer4dealer_invoice_item');
        $connection = $installer->getConnection();

        $connection->dropColumn($invoiceTable, 'order_id');
        $connection->addColumn($orderTable, 'invoice_id', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            'nullable' => true,
            'comment' => "Invoice ID"
        ]);

        $connection->addColumn($invoiceItemTable, 'order_id', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            'nullable' => true,
            'comment' => "Order ID"
        ]);
    }

    public function seperateOrderInvoiceTable($installer)
    {
        $connection = $installer->getConnection();
        $connection->dropColumn($installer->getTable(InstallSchema::ORDER_TABLE), 'invoice_id');
        $connection->addColumn($installer->getTable('dealer4dealer_orderitem'), 'invoice_id', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            'nullable' => true,
            'comment' => "Invoice ID"
        ]);

        $table = $installer->getConnection()->newTable($installer->getTable('dealer4dealer_orderinvoicerelation'));
        $table->addColumn(
            'orderinvoicerelation_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
            'Entity ID'
        )->addColumn(
            'order_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'order_id'
        )->addColumn(
            'invoice_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'invoice_id'
        );

        $installer->getConnection()->createTable($table);
    }

    /**
     * Will add indexes to the following columns:
     *  + dealer4dealer_order
     *    - magento_order_id
     *  + dealer4dealer_invoice
     *    - magento_invoice_id
     * @param SetupInterface $installer
     */
    public function addIndexToFields($installer)
    {
        /** @var  $installer */

        $installer->getConnection()->modifyColumn(
            $installer->getTable("dealer4dealer_order"),
            "magento_increment_id",
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                "length" => 255,
            ]
        );
        $installer->getConnection()->modifyColumn(
            $installer->getTable("dealer4dealer_invoice"),
            "magento_increment_id",
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                "length" => 255,
            ]
        );

        $installer->getConnection()->addIndex(
            $orderTable = $installer->getTable("dealer4dealer_order"),
            "INDEX_MAGENTO_INCREMENT_ID",
            ["magento_increment_id"]
        );

        $installer->getConnection()->addIndex(
            $orderTable = $installer->getTable("dealer4dealer_invoice"),
            "INDEX_MAGENTO_INCREMENT_ID",
            ["magento_increment_id"]
        );
    }

    /**
     * Create new table for attachments
     *
     * @param SetupInterface $installer
     * @throws \Zend_Db_Exception
     */
    public function attachmentTables($installer)
    {
        $table_dealer4dealer_substituteorders_attachment = $installer->getConnection()->newTable($installer->getTable('dealer4dealer_substituteorders_attachment'));

        $table_dealer4dealer_substituteorders_attachment->addColumn(
            'attachment_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
            'Entity ID'
        );


        $table_dealer4dealer_substituteorders_attachment->addColumn(
            'magento_customer_identifier',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'magento_customer_identifier'
        );


        $table_dealer4dealer_substituteorders_attachment->addColumn(
            'file',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'file'
        );


        $table_dealer4dealer_substituteorders_attachment->addColumn(
            'entity_type',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'entity_type'
        );

        $table_dealer4dealer_substituteorders_attachment->addColumn(
            'entity_type_identifier',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'entity_type_identifier'
        );

        $installer->getConnection()->createTable($table_dealer4dealer_substituteorders_attachment);
    }

    /**
     * Add 'external customer id' column and make 'magento customer id' column nullable.
     * 
     * @param SetupInterface $installer
     */
    public function addExternalCustomerField($installer){
        $table = $installer->getTable(InstallSchema::ORDER_TABLE);
        
        $columns = [
            'external_customer_id' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'nullable' => true,
                'comment' => "External customer id"
            ]
        ];
        
        $connection = $installer->getConnection();
        
        foreach ($columns as $name => $definition) {
            $connection->addColumn($table, $name, $definition);
        }
        
        $installer->getConnection()->modifyColumn(
            $table,
            "magento_customer_id",
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'nullable' => true
            ]
            );
    }

    /**
     * Add 'shippingMethod' column to the shipments table
     *
     * @param SetupInterface $installer
     */
    public function addShippingMethodField($installer){
        $table = $installer->getTable(InstallSchema::SHIPMENT_TABLE);

        $columns = [
            'shipping_method' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => "Shipping method for the shipment"
            ]
        ];

        $connection = $installer->getConnection();

        foreach($columns as $name => $definition){
            $connection->addColumn($table, $name, $definition);
        }
    }
}
