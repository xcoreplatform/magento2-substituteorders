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

namespace Dealer4Dealer\SubstituteOrders\Model;

use Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentItemInterface;
use Dealer4Dealer\SubstituteOrders\Model\ResourceModel\ShipmentItem as ResourceShipmentItem;

class ShipmentItem extends \Magento\Framework\Model\AbstractModel implements ShipmentItemInterface
{
    /**
     * @var string
     */
    const ENTITY = 'shipment_item';

    /**
     * @var string
     */
    protected $_eventPrefix = 'substitute_order_shipment_item';

    /**
     * @var string
     */
    protected $_eventObject = 'item';

    protected $_additionalData;

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceShipmentItem::class);
    }

    public function save()
    {
        if ($this->_additionalData) {
            $data = [];
            foreach ($this->_additionalData as $value) {
                $data[$value->getKey()] = $value->getValue();
            }

            $this->setData(self::ADDITIONAL_DATA, json_encode($data));
        }

        return parent::save();
    }

    /**
     * @inheritDoc
     */
    public function getShipmentitemId()
    {
        return $this->getData(self::SHIPMENTITEM_ID);
    }

    /**
     * @inheritDoc
     */
    public function setShipmentitemId($shipmentitemId)
    {
        return $this->setData(self::SHIPMENTITEM_ID, $shipmentitemId);
    }

    /**
     * @inheritDoc
     */
    public function getShipmentId()
    {
        return $this->getData(self::SHIPMENT_ID);
    }

    /**
     * @inheritDoc
     */
    public function setShipmentId($shipment_id)
    {
        return $this->setData(self::SHIPMENT_ID, $shipment_id);
    }

    /**
     * @inheritDoc
     */
    public function getRowTotal()
    {
        return $this->getData(self::ROW_TOTAL);
    }

    /**
     * @inheritDoc
     */
    public function setRowTotal($row_total)
    {
        return $this->setData(self::ROW_TOTAL, $row_total);
    }

    /**
     * @inheritDoc
     */
    public function getPrice()
    {
        return $this->getData(self::PRICE);
    }

    /**
     * @inheritDoc
     */
    public function setPrice($price)
    {
        return $this->setData(self::PRICE, $price);
    }

    /**
     * @inheritDoc
     */
    public function getWeight()
    {
        return $this->getData(self::WEIGHT);
    }

    /**
     * @inheritDoc
     */
    public function setWeight($weight)
    {
        return $this->setData(self::WEIGHT, $weight);
    }

    /**
     * @inheritDoc
     */
    public function getQty()
    {
        return $this->getData(self::QTY);
    }

    /**
     * @inheritDoc
     */
    public function setQty($qty)
    {
        return $this->setData(self::QTY, $qty);
    }

    /**
     * @inheritDoc
     */
    public function getSku()
    {
        return $this->getData(self::SKU);
    }

    /**
     * @inheritDoc
     */
    public function setSku($sku)
    {
        return $this->setData(self::SKU, $sku);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * @inheritDoc
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * @inheritDoc
     */
    public function getAdditionalData()
    {
        if ($this->_additionalData == null) {
            $this->_additionalData = [];

            if ($this->getData(self::ADDITIONAL_DATA)) {
                $data = json_decode($this->getData(self::ADDITIONAL_DATA), true);
                foreach ($data as $key => $value) {
                    $this->_additionalData[] = new AdditionalData($key, $value);
                }
            }
        }
        return $this->_additionalData;
    }

    /**
     * @inheritDoc
     */
    public function setAdditionalData($additional_data)
    {
        $this->_additionalData = $additional_data;
        return $this;
    }
}
