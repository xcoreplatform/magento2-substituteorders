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

namespace Dealer4Dealer\SubstituteOrders\Api\Data;

interface InvoiceInterface
{
    const ORDER_IDS = 'order_ids';
    const BASE_SUBTOTAL = 'base_subtotal';
    const PO_NUMBER = 'po_number';
    const BASE_GRANDTOTAL = 'base_grandtotal';
    const SUBTOTAL = 'subtotal';
    const INVOICE_ID = 'invoice_id';
    const STATE = 'state';
    const BILLING_ADDRESS_ID = 'billing_address_id';
    const BASE_DISCOUNT_AMOUNT = 'base_discount_amount';
    const MAGENTO_CUSTOMER_ID = 'magento_customer_id';
    const EXT_INVOICE_ID = 'ext_invoice_id';
    const SHIPPING_AMOUNT = 'shipping_amount';
    const BASE_TAX_AMOUNT = 'base_tax_amount';
    const BASE_SHIPPING_AMOUNT = 'base_shipping_amount';
    const GRANDTOTAL = 'grandtotal';
    const MAGENTO_INVOICE_ID = 'magento_invoice_id';
    const SHIPPING_ADDRESS_ID = 'shipping_address_id';
    const INVOICE_DATE = 'invoice_date';
    const UPDATED_AT = 'updated_at';
    const TAX_AMOUNT = 'tax_amount';
    const DISCOUNT_AMOUNT = 'discount_amount';
    const MAGENTO_INCREMENT_ID = 'magento_increment_id';
    const ADDITIONAL_DATA = 'additional_data';
    const FILE_CONTENT = 'file_content';

    /**
     * Get invoice_id
     * @return string|null
     */
    public function getInvoiceId();

    /**
     * Set invoice_id
     * @param string $invoice_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function setInvoiceId($invoiceId);

    /**
     * Get order_id
     * @return int[]|null
     */
    public function getOrderIds();

    /**
     * Set order_id
     * @param int[] $order_ids
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function setOrderIds($orderIds);

    /**
     * Get magento_invoice_id
     * @return string|null
     */
    public function getMagentoInvoiceId();

    /**
     * Set magento_invoice_id
     * @param string $magento_invoice_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function setMagentoInvoiceId($magento_invoice_id);

    /**
     * Get ext_invoice_id
     * @return string|null
     */
    public function getExtInvoiceId();

    /**
     * Set ext_invoice_id
     * @param string $ext_invoice_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function setExtInvoiceId($ext_invoice_id);

    /**
     * Get po_number
     * @return string|null
     */
    public function getPoNumber();

    /**
     * Set po_number
     * @param string $po_number
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function setPoNumber($po_number);

    /**
     * Get magento_customer_id
     * @return string|null
     */
    public function getMagentoCustomerId();

    /**
     * Set magento_customer_id
     * @param string $magento_customer_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function setMagentoCustomerId($magento_customer_id);

    /**
     * Get base_tax_amount
     * @return string|null
     */
    public function getBaseTaxAmount();

    /**
     * Set base_tax_amount
     * @param string $base_tax_amount
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function setBaseTaxAmount($base_tax_amount);

    /**
     * Get base_discount_amount
     * @return string|null
     */
    public function getBaseDiscountAmount();

    /**
     * Set base_discount_amount
     * @param string $base_discount_amount
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function setBaseDiscountAmount($base_discount_amount);

    /**
     * Get base_shipping_amount
     * @return string|null
     */
    public function getBaseShippingAmount();

    /**
     * Set base_shipping_amount
     * @param string $base_shipping_amount
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function setBaseShippingAmount($base_shipping_amount);

    /**
     * Get base_subtotal
     * @return string|null
     */
    public function getBaseSubtotal();

    /**
     * Set base_subtotal
     * @param string $base_subtotal
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function setBaseSubtotal($base_subtotal);

    /**
     * Get base_grandtotal
     * @return string|null
     */
    public function getBaseGrandtotal();

    /**
     * Set base_grandtotal
     * @param string $base_grandtotal
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function setBaseGrandtotal($base_grandtotal);

    /**
     * Get tax_amount
     * @return string|null
     */
    public function getTaxAmount();

    /**
     * Set tax_amount
     * @param string $tax_amount
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function setTaxAmount($tax_amount);

    /**
     * Get discount_amount
     * @return string|null
     */
    public function getDiscountAmount();

    /**
     * Set discount_amount
     * @param string $discount_amount
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function setDiscountAmount($discount_amount);

    /**
     * Get shipping_amount
     * @return string|null
     */
    public function getShippingAmount();

    /**
     * Set shipping_amount
     * @param string $shipping_amount
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function setShippingAmount($shipping_amount);

    /**
     * Get subtotal
     * @return string|null
     */
    public function getSubtotal();

    /**
     * Set subtotal
     * @param string $subtotal
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function setSubtotal($subtotal);

    /**
     * Get grandtotal
     * @return string|null
     */
    public function getGrandtotal();

    /**
     * Set grandtotal
     * @param string $grandtotal
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function setGrandtotal($grandtotal);

    /**
     * Get invoice_date
     * @return string|null
     */
    public function getInvoiceDate();

    /**
     * Set invoice_date
     * @param string $invoice_date
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function setInvoiceDate($invoice_date);

    /**
     * Get state
     * @return string|null
     */
    public function getState();

    /**
     * Set state
     * @param string $state
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function setState($state);

    /**
     * Get Magento increment id
     * @return string|null
     */
    public function getMagentoIncrementId();

    /**
     * Set state
     * @param string $state
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function setMagentoIncrementId($magentoIncrementId);

    /**
     * Get additional_data
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\AdditionalDataInterface[]
     */
    public function getAdditionalData();

    /**
     * Set additional_data
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\AdditionalDataInterface[] $additional_data
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function setAdditionalData($additional_data);

    /**
     * Set items
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceItemInterface[] $items
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function setItems(array $items);

    /**
     * Get order items
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceItemInterface[]
     */
    public function getItems();

    /**
     * Get shipping_address_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface|null
     */
    public function getShippingAddress();

    /**
     * Set shipping_address_id
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface $shipping_address
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function setShippingAddress(\Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface $shipping_address);

    /**
     * Get billing_address_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface|null
     */
    public function getBillingAddress();

    /**
     * Set billing_address_id
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface $billing_address
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function setBillingAddress(\Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface $billing_address_id);

    /**
     * Return attachments
     *
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\File\ContentInterface[]|null
     */
    public function getAttachments();

    /**
     * Set attachments
     *
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\File\ContentInterface[] $fileContent
     * @return $this
     */
    public function setAttachments(array $fileContent);
}
