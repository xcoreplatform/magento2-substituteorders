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

interface OrderItemInterface
{

    const ORDERITEM_ID          = 'orderitem_id';
    const ORDER_ID              = 'order_id';
    const INVOICE_ID            = 'invoice_id';
    const SKU                   = 'sku';
    const NAME                  = 'name';
    const PRICE                 = 'price';
    const QTY                   = 'qty';
    const TAX_AMOUNT            = 'tax_amount';
    const DISCOUNT_AMOUNT       = 'discount_amount';
    const ROW_TOTAL             = 'row_total';
    const BASE_PRICE            = 'base_price';
    const BASE_TAX_AMOUNT       = 'base_tax_amount';
    const BASE_DISCOUNT_AMOUNT  = 'base_discount_amount';
    const BASE_ROW_TOTAL        = 'base_row_total';
    const ADDITIONAL_DATA       = 'additional_data';


    /**
     * Get orderitem_id
     * @return string|null
     */
    public function getOrderitemId();

    /**
     * Set orderitem_id
     * @param string $orderitem_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setOrderitemId($orderitemId);

    /**
     * Get order_id
     * @return string|null
     */
    public function getOrder();

    /**
     * Set order_id
     * @param string $order_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setOrder($order_id);

    /**
     * Get order_id
     * @return string|null
     */
    public function getOrderId();

    /**
     * Set order_id
     * @param string $order_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setOrderId($order_id);

    /**
     * Get invoice id
     * @return string|null
     */
    public function getInvoiceId();

    /**
     * Set invoiceId
     * @param string $invoiceId
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setInvoiceId($invoiceId);

    /**
     * Get name
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     * @param string $name
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setName($name);

    /**
     * Get sku
     * @return string|null
     */
    public function getSku();

    /**
     * Set sku
     * @param string $sku
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setSku($sku);

    /**
     * Get base_price
     * @return string|null
     */
    public function getBasePrice();

    /**
     * Set base_price
     * @param string $base_price
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setBasePrice($base_price);

    /**
     * Get price
     * @return string|null
     */
    public function getPrice();

    /**
     * Set price
     * @param string $price
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setPrice($price);

    /**
     * Get base_row_total
     * @return string|null
     */
    public function getBaseRowTotal();

    /**
     * Set base_row_total
     * @param string $base_row_total
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setBaseRowTotal($base_row_total);

    /**
     * Get row_total
     * @return string|null
     */
    public function getRowTotal();

    /**
     * Set row_total
     * @param string $row_total
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setRowTotal($row_total);

    /**
     * Get base_tax_amount
     * @return string|null
     */
    public function getBaseTaxAmount();

    /**
     * Set base_tax_amount
     * @param string $base_tax_amount
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
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
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setTaxAmount($tax_amount);

    /**
     * Get qty
     * @return string|null
     */
    public function getQty();

    /**
     * Set qty
     * @param string $qty
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setQty($qty);

    /**
     * Get additional_data
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\AdditionalDataInterface[]
     */
    public function getAdditionalData();

    /**
     * Set additional_data
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\AdditionalDataInterface[] $additional_data
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setAdditionalData($additional_data);

    /**
     * Get base_discount_amount
     * @return string|null
     */
    public function getBaseDiscountAmount();

    /**
     * Set base_discount_amount
     * @param string $base_discount_amount
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
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
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setDiscountAmount($discount_amount);
}
