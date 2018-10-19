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

interface OrderInterface
{
    const ORDER_ID              = 'order_id';
    const INVOICE_IDS           = 'invoice_ids';
    const MAGENTO_ORDER_ID      = 'magento_order_id';
    const EXT_ORDER_ID          = 'ext_order_id';
    const PO_NUMBER             = 'po_number';
    const MAGENTO_CUSTOMER_ID   = 'magento_customer_id';
    const SHIPPING_ADDRESS_ID   = 'shipping_address_id';
    const BILLING_ADDRESS_ID    = 'billing_address_id';
    const BASE_TAX_AMOUNT       = 'base_tax_amount';
    const BASE_DISCOUNT_AMOUNT  = 'base_discount_amount';
    const BASE_SHIPPING_AMOUNT  = 'base_shipping_amount';
    const BASE_SUBTOTAL         = 'base_subtotal';
    const BASE_GRANDTOTAL       = 'base_grandtotal';
    const SHIPPING_METHOD       = 'shipping_method';
    const TAX_AMOUNT            = 'tax_amount';
    const DISCOUNT_AMOUNT       = 'discount_amount';
    const SHIPPING_AMOUNT       = 'shipping_amount';
    const SUBTOTAL              = 'subtotal';
    const GRANDTOTAL            = 'grandtotal';
    const ORDER_DATE            = 'order_date';
    const STATE                 = 'state';
    const PAYMENT_METHOD        = 'payment_method';
    const ADDITIONAL_DATA       = 'additional_data';
    const MAGENTO_INCREMENT_ID  = 'magento_increment_id';
    const UPDATED_AT            = 'updated_at';
    const ITEMS                 = 'items';
    const FILE_CONTENT          = 'file_content';
    const EXTERNAL_CUSTOMER_ID  = 'external_customer_id';

    /**
     * Get order_id
     * @return string|null
     */
    public function getOrderId();

    /**
     * Set order_id
     * @param string $order_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    public function setOrderId($orderId);

    /**
     * Get invoice ids
     * @return int[]|null
     */
    public function getInvoiceIds();

    /**
     * Set invoice ids
     * @param int[] $invoiceIds
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    public function setInvoiceIds($invoiceIds);

    /**
     * Get magento_order_id
     * @return string|null
     */
    
    public function getMagentoOrderId();

    /**
     * Set magento_order_id
     * @param string $magento_order_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    
    public function setMagentoOrderId($magento_order_id);

    /**
     * Get magento_customer_id
     * @return string|null
     */
    
    public function getMagentoCustomerId();

    /**
     * Set magento_customer_id
     * @param string $magento_customer_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    
    public function setMagentoCustomerId($magento_customer_id);

    /**
     * Get external_customer_id
     * @return string|null
     */
    public function getExternalCustomerId();
    
    /**
     * Set external_customer_id
     * @param string $external_customer_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    public function setExternalCustomerId($external_customer_id);
    
    /**
     * Get ext_order_id
     * @return string|null
     */
    
    public function getExtOrderId();

    /**
     * Set ext_order_id
     * @param string $ext_order_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    
    public function setExtOrderId($ext_order_id);

    /**
     * Get base_grandtotal
     * @return string|null
     */
    
    public function getBaseGrandtotal();

    /**
     * Set base_grandtotal
     * @param string $base_grandtotal
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    
    public function setBaseGrandtotal($base_grandtotal);

    /**
     * Get base_subtotal
     * @return string|null
     */
    
    public function getBaseSubtotal();

    /**
     * Set base_subtotal
     * @param string $base_subtotal
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    
    public function setBaseSubtotal($base_subtotal);

    /**
     * Get grandtotal
     * @return string|null
     */
    
    public function getGrandtotal();

    /**
     * Set grandtotal
     * @param string $grandtotal
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    
    public function setGrandtotal($grandtotal);

    /**
     * Get subtotal
     * @return string|null
     */
    
    public function getSubtotal();

    /**
     * Set subtotal
     * @param string $subtotal
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    
    public function setSubtotal($subtotal);

    /**
     * Get po_number
     * @return string|null
     */
    
    public function getPoNumber();

    /**
     * Set po_number
     * @param string $po_number
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    
    public function setPoNumber($po_number);

    /**
     * Get state
     * @return string|null
     */
    
    public function getState();

    /**
     * Set state
     * @param string $state
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    
    public function setState($state);

    /**
     * Get shipping_method
     * @return string|null
     */
    
    public function getShippingMethod();

    /**
     * Set shipping_method
     * @param string $shipping_method
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    
    public function setShippingMethod($shipping_method);

    /**
     * Get shipping_address_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface|null
     */
    
    public function getShippingAddress();

    /**
     * Set shipping_address_id
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface $shipping_address_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    
    public function setShippingAddress(\Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface $shipping_address);

    /**
     * Get billing_address_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface|null
     */
    
    public function getBillingAddress();

    /**
     * Set billing_address_id
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface $billing_address_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    
    public function setBillingAddress(\Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface $billing_address_id);

    /**
     * Get payment_method
     * @return string|null
     */
    
    public function getPaymentMethod();

    /**
     * Set payment_method
     * @param string $payment_method
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    
    public function setPaymentMethod($payment_method);

    /**
     * Get base_discount_amount
     * @return string|null
     */
    
    public function getBaseDiscountAmount();

    /**
     * Set base_discount_amount
     * @param string $base_discount_amount
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    
    public function setBaseDiscountAmount($base_discount_amount);

    /**
     * Get discount_amount
     * @return string|null
     */
    
    public function getDiscountAmount();

    /**
     * Set discount_amount
     * @param string $discount_amount
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    
    public function setDiscountAmount($discount_amount);

    /**
     * Get order_date
     * @return string|null
     */
    
    public function getOrderDate();

    /**
     * Set order_date
     * @param string $order_date
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    
    public function setOrderDate($order_date);

    /**
     * Get base_tax_amount
     * @return string|null
     */
    
    public function getBaseTaxAmount();

    /**
     * Set base_tax_amount
     * @param string $base_tax_amount
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    
    public function setBaseTaxAmount($base_tax_amount);

    /**
     * Get tax_amount
     * @return string|null
     */
    
    public function getTaxAmount();

    /**
     * Set tax_amount
     * @param string $tax_amount
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    
    public function setTaxAmount($tax_amount);

    /**
     * Get base_shipping_amount
     * @return string|null
     */
    
    public function getBaseShippingAmount();

    /**
     * Set base_shipping_amount
     * @param string $base_shipping_amount
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    
    public function setBaseShippingAmount($base_shipping_amount);

    /**
     * Get shipping_amount
     * @return string|null
     */
    
    public function getShippingAmount();

    /**
     * Set shipping_amount
     * @param string $shipping_amount
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    
    public function setShippingAmount($shipping_amount);
    
    /**
     * Set items
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface[] $items
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    public function setItems(array $items);
    
    /**
     * Get order items
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface[]
     */
    public function getItems();

    /**
     * Get magento_increment_id
     * @return string|null
     */
    public function getMagentoIncrementId();

    /**
     * Set magento_increment_id
     * @param string $incrementId
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    public function setMagentoIncrementId($incrementId);

    /**
     * Get updated_at
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated_at
     * @param string $updated
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    public function setUpdatedAt($updated);
   
    /**
     * Get additional_data
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\AdditionalDataInterface[]
     */
    public function getAdditionalData();
    
    /**
     * Set additional_data
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\AdditionalDataInterface[] $additional_data
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    public function setAdditionalData($additional_data);

    /**
     * Return attachements
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
