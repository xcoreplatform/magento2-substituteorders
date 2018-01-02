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

interface ShipmentItemInterface
{
    const WEIGHT = 'weight';
    const QTY = 'qty';
    const PRICE = 'price';
    const ROW_TOTAL = 'row_total';
    const SHIPMENT_ID = 'shipment_id';
    const NAME = 'name';
    const DESCRIPTION = 'description';
    const ADDITIONAL_DATA = 'additional_data';
    const SHIPMENTITEM_ID = 'shipmentitem_id';
    const SKU = 'sku';

    /**
     * Get shipmentitem_id
     * @return string|null
     */
    public function getShipmentitemId();

    /**
     * Set shipmentitem_id
     * @param string $shipmentitem_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentItemInterface
     */
    public function setShipmentitemId($shipmentitemId);

    /**
     * Get shipment_id
     * @return string|null
     */

    public function getShipmentId();

    /**
     * Set shipment_id
     * @param string $shipment_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentItemInterface
     */

    public function setShipmentId($shipment_id);

    /**
     * Get row_total
     * @return string|null
     */

    public function getRowTotal();

    /**
     * Set row_total
     * @param string $row_total
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentItemInterface
     */

    public function setRowTotal($row_total);

    /**
     * Get price
     * @return string|null
     */

    public function getPrice();

    /**
     * Set price
     * @param string $price
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentItemInterface
     */

    public function setPrice($price);

    /**
     * Get weight
     * @return string|null
     */

    public function getWeight();

    /**
     * Set weight
     * @param string $weight
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentItemInterface
     */

    public function setWeight($weight);

    /**
     * Get qty
     * @return string|null
     */

    public function getQty();

    /**
     * Set qty
     * @param string $qty
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentItemInterface
     */

    public function setQty($qty);

    /**
     * Get sku
     * @return string|null
     */

    public function getSku();

    /**
     * Set sku
     * @param string $sku
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentItemInterface
     */

    public function setSku($sku);

    /**
     * Get name
     * @return string|null
     */

    public function getName();

    /**
     * Set name
     * @param string $name
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentItemInterface
     */

    public function setName($name);

    /**
     * Get description
     * @return string|null
     */

    public function getDescription();

    /**
     * Set description
     * @param string $description
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentItemInterface
     */

    public function setDescription($description);

    /**
     * Get additional_data
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\AdditionalDataInterface[]
     */

    public function getAdditionalData();

    /**
     * Set additional_data
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\AdditionalDataInterface[] $additional_data
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentItemInterface
     */

    public function setAdditionalData($additional_data);
}
