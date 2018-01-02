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

interface ShipmentInterface
{
    const EXT_SHIPMENT_ID = 'ext_shipment_id';
    const INVOICE_ID = 'invoice_id';
    const SHIPMENT_STATUS = 'shipment_status';
    const BILLING_ADDRESS_ID = 'billing_address_id';
    const SHIPMENT_ID = 'shipment_id';
    const ORDER_ID = 'order_id';
    const NAME = 'name';
    const CUSTOMER_ID = 'customer_id';
    const UPDATED_AT = 'updated_at';
    const SHIPPING_ADDRESS_ID = 'shipping_address_id';
    const INCREMENT_ID = 'increment_id';
    const CREATED_AT = 'created_at';
    const TRACKING = 'tracking';
    const ADDITIONAL_DATA = 'additional_data';
    const FILE_CONTENT = 'file_content';

    /**
     * Get shipment_id
     * @return string|null
     */
    public function getShipmentId();

    /**
     * Set shipment_id
     * @param string $shipment_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentInterface
     */
    public function setShipmentId($shipmentId);

    /**
     * Get ext_shipment_id
     * @return string|null
     */
    public function getExtShipmentId();

    /**
     * Set ext_shipment_id
     * @param string $shipment_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentInterface
     */
    public function setExtShipmentId($shipmentId);

    /**
     * Get customer_id
     * @return string|null
     */
    public function getCustomerId();

    /**
     * Set customer_id
     * @param string $customer_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentInterface
     */
    public function setCustomerId($customer_id);

    /**
     * Get order_id
     * @return string|null
     */
    public function getOrderId();

    /**
     * Set order_id
     * @param string $order_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentInterface
     */
    public function setOrderId($order_id);

    /**
     * Get invoice_id
     * @return string|null
     */
    public function getInvoiceId();

    /**
     * Set invoice_id
     * @param string $invoice_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentInterface
     */
    public function setInvoiceId($invoice_id);

    /**
     * Get shipment_status
     * @return string|null
     */
    public function getShipmentStatus();

    /**
     * Set shipment_status
     * @param string $shipment_status
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentInterface
     */
    public function setShipmentStatus($shipment_status);

    /**
     * Get increment_id
     * @return string|null
     */
    public function getIncrementId();

    /**
     * Set increment_id
     * @param string $increment_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentInterface
     */
    public function setIncrementId($increment_id);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $created_at
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentInterface
     */
    public function setCreatedAt($created_at);

    /**
     * Get updated_at
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated_at
     * @param string $updated_at
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentInterface
     */
    public function setUpdatedAt($updated_at);

    /**
     * Get name
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     * @param string $name
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentInterface
     */
    public function setName($name);

    /**
     * Get tracking
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentTrackingInterface[]
     */
    public function getTracking();

    /**
     * Set tracking
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentTrackingInterface[] $tracking
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentInterface
     */
    public function setTracking($tracking);

    /**
     * Get additional_data
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\AdditionalDataInterface[]
     */
    public function getAdditionalData();

    /**
     * Set additional_data
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\AdditionalDataInterface[] $additional_data
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentInterface
     */
    public function setAdditionalData($additional_data);

    /**
     * Set items
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentItemInterface[] $items
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentInterface
     */
    public function setItems(array $items);

    /**
     * Get order items
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentItemInterface[]
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
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentInterface
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
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentInterface
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
