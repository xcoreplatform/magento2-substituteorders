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

use Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface;

class OrderItem extends \Magento\Framework\Model\AbstractModel implements OrderItemInterface
{

    /**
     * @var string
     */
    const ENTITY = 'order_item';

    /**
     * @var string
     */
    protected $_eventPrefix = 'substitute_order_order_item';

    /**
     * @var string
     */
    protected $_eventObject = 'item';

    /*
     * @var \Dealer4Dealer\SubstituteOrders\Model\OrderFactory
     */
    protected $orderFactory;

    protected $_order = null;
    protected $_additionalData = null;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Dealer4Dealer\SubstituteOrders\Model\OrderFactory $orderFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->orderFactory = $orderFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Dealer4Dealer\SubstituteOrders\Model\ResourceModel\OrderItem');
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
     * Get orderitem_id
     * @return string
     */
    public function getOrderitemId()
    {
        return $this->getData(self::ORDERITEM_ID);
    }

    /**
     * Set orderitem_id
     * @param string $orderitemId
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setOrderitemId($orderitemId)
    {
        return $this->setData(self::ORDERITEM_ID, $orderitemId);
    }

    /**
     * Get order
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    public function getOrder()
    {
        if ($this->_order === null && ($orderId = $this->getOrderId())) {
            $order = $this->orderFactory->create();
            # TODO: Order save crashes on this line
            //            $this->_order = $order->load($orderId);
        }
        return $this->_order;
    }

    /**
     * Set order
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface $order
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setOrder($order)
    {
        $this->_order = $order;
        $this->setOrderId($order->getId());
        return $this;
    }

    /**
     * Set order_id
     * @param string $order_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setOrderId($orderId)
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * Get order_id
     * @return string
     */
    public function getOrderId()
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * @inheritdoc
     */
    public function setInvoiceId($invoiceId)
    {
        return $this->setData(self::INVOICE_ID, $invoiceId);
    }

    /**
     * @inheritdoc
     */
    public function getInvoiceId()
    {
        return $this->getData(self::INVOICE_ID);
    }

    /**
     * Get name
     * @return string
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * Set name
     * @param string $name
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Get sku
     * @return string
     */
    public function getSku()
    {
        return $this->getData(self::SKU);
    }

    /**
     * Set sku
     * @param string $sku
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setSku($sku)
    {
        return $this->setData(self::SKU, $sku);
    }

    /**
     * Get base_price
     * @return string
     */
    public function getBasePrice()
    {
        return $this->getData(self::BASE_PRICE);
    }

    /**
     * Set base_price
     * @param string $base_price
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setBasePrice($base_price)
    {
        return $this->setData(self::BASE_PRICE, $base_price);
    }

    /**
     * Get price
     * @return string
     */
    public function getPrice()
    {
        return $this->getData(self::PRICE);
    }

    /**
     * Set price
     * @param string $price
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setPrice($price)
    {
        return $this->setData(self::PRICE, $price);
    }

    /**
     * Get base_row_total
     * @return string
     */
    public function getBaseRowTotal()
    {
        return $this->getData(self::BASE_ROW_TOTAL);
    }

    /**
     * Set base_row_total
     * @param string $base_row_total
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setBaseRowTotal($base_row_total)
    {
        return $this->setData(self::BASE_ROW_TOTAL, $base_row_total);
    }

    /**
     * Get row_total
     * @return string
     */
    public function getRowTotal()
    {
        return $this->getData(self::ROW_TOTAL);
    }

    /**
     * Set row_total
     * @param string $row_total
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setRowTotal($row_total)
    {
        return $this->setData(self::ROW_TOTAL, $row_total);
    }

    /**
     * Get base_tax_amount
     * @return string
     */
    public function getBaseTaxAmount()
    {
        return $this->getData(self::BASE_TAX_AMOUNT);
    }

    /**
     * Set base_tax_amount
     * @param string $base_tax_amount
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setBaseTaxAmount($base_tax_amount)
    {
        return $this->setData(self::BASE_TAX_AMOUNT, $base_tax_amount);
    }

    /**
     * Get tax_amount
     * @return string
     */
    public function getTaxAmount()
    {
        return $this->getData(self::TAX_AMOUNT);
    }

    /**
     * Set tax_amount
     * @param string $tax_amount
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setTaxAmount($tax_amount)
    {
        return $this->setData(self::TAX_AMOUNT, $tax_amount);
    }

    /**
     * Get qty
     * @return string
     */
    public function getQty()
    {
        return $this->getData(self::QTY);
    }

    /**
     * Set qty
     * @param string $qty
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
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
     * Get base_discount_amount
     * @return string
     */
    public function getBaseDiscountAmount()
    {
        return $this->getData(self::BASE_DISCOUNT_AMOUNT);
    }

    /**
     * Set base_discount_amount
     * @param string $base_discount_amount
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setBaseDiscountAmount($base_discount_amount)
    {
        return $this->setData(self::BASE_DISCOUNT_AMOUNT, $base_discount_amount);
    }

    /**
     * Get discount_amount
     * @return string
     */
    public function getDiscountAmount()
    {
        return $this->getData(self::DISCOUNT_AMOUNT);
    }

    /**
     * Set discount_amount
     * @param string $discount_amount
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface
     */
    public function setDiscountAmount($discount_amount)
    {
        return $this->setData(self::DISCOUNT_AMOUNT, $discount_amount);
    }

    public function setData($key, $value = null)
    {
        parent::setData($key, $value);
        return $this;
    }
}
