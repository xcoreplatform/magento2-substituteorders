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

use Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceItemInterface;

class InvoiceItem extends \Magento\Framework\Model\AbstractModel implements InvoiceItemInterface
{

    /**
     * @var string
     */
    const ENTITY = 'invoice_item';

    /**
     * @var string
     */
    protected $_eventPrefix = 'substitute_order_invoice_item';

    /**
     * @var string
     */
    protected $_eventObject = 'item';

    protected $_additionalData = null;

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Dealer4Dealer\SubstituteOrders\Model\ResourceModel\InvoiceItem');
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
    public function getInvoiceitemId()
    {
        return $this->getData(self::INVOICEITEM_ID);
    }

    /**
     * @inheritDoc
     */
    public function setInvoiceitemId($invoiceItemId)
    {
        return $this->setData(self::INVOICEITEM_ID, $invoiceItemId);
    }

    /**
     * @inheritDoc
     */
    public function setInvoiceId($invoiceId)
    {
        return $this->setData(self::INVOICE_ID, $invoiceId);
    }

    /**
     * @inheritDoc
     */
    public function getInvoiceId()
    {
        return $this->getData(self::INVOICE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setOrderId($orderId)
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * @inheritDoc
     */
    public function getOrderId()
    {
        return $this->getData(self::ORDER_ID);
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
    public function getBasePrice()
    {
        return $this->getData(self::BASE_PRICE);
    }

    /**
     * @inheritDoc
     */
    public function setBasePrice($base_price)
    {
        return $this->setData(self::BASE_PRICE, $base_price);
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
    public function getBaseRowTotal()
    {
        return $this->getData(self::BASE_ROW_TOTAL);
    }

    /**
     * @inheritDoc
     */
    public function setBaseRowTotal($base_row_total)
    {
        return $this->setData(self::BASE_ROW_TOTAL, $base_row_total);
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
    public function getBaseTaxAmount()
    {
        return $this->getData(self::BASE_TAX_AMOUNT);
    }

    /**
     * @inheritDoc
     */
    public function setBaseTaxAmount($base_tax_amount)
    {
        return $this->setData(self::BASE_TAX_AMOUNT, $base_tax_amount);
    }

    /**
     * @inheritDoc
     */
    public function getTaxAmount()
    {
        return $this->getData(self::TAX_AMOUNT);
    }

    /**
     * @inheritDoc
     */
    public function setTaxAmount($tax_amount)
    {
        return $this->setData(self::TAX_AMOUNT, $tax_amount);
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

    /**
     * @inheritDoc
     */
    public function getBaseDiscountAmount()
    {
        return $this->getData(self::BASE_DISCOUNT_AMOUNT);
    }

    /**
     * @inheritDoc
     */
    public function setBaseDiscountAmount($base_discount_amount)
    {
        return $this->setData(self::BASE_DISCOUNT_AMOUNT, $base_discount_amount);
    }

    /**
     * @inheritDoc
     */
    public function getDiscountAmount()
    {
        return $this->getData(self::DISCOUNT_AMOUNT);
    }

    /**
     * @inheritDoc
     */
    public function setDiscountAmount($discount_amount)
    {
        return $this->setData(self::DISCOUNT_AMOUNT, $discount_amount);
    }
}
