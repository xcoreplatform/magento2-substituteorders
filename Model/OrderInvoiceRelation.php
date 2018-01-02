<?php
/**
 * A Magento 2 module named Dealer4Dealer/SubstituteOrders
 * Copyright (C) 2017 Maikel Martens
 *
 * This file is part of Dealer4Dealer/SubstituteOrders.
 *
 * Dealer4Dealer/SubstituteOrders is free software: you can redistribute it and/or modify
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

use Dealer4Dealer\SubstituteOrders\Api\Data\OrderInvoiceRelationInterface;

class OrderInvoiceRelation extends \Magento\Framework\Model\AbstractModel implements OrderInvoiceRelationInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Dealer4Dealer\SubstituteOrders\Model\ResourceModel\OrderInvoiceRelation');
    }

    /**
     * Get orderinvoicerelation_id
     * @return string
     */
    public function getOrderinvoicerelationId()
    {
        return $this->getData(self::ORDERINVOICERELATION_ID);
    }

    /**
     * Set orderinvoicerelation_id
     * @param string $orderinvoicerelationId
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInvoiceRelationInterface
     */
    public function setOrderinvoicerelationId($orderinvoicerelationId)
    {
        return $this->setData(self::ORDERINVOICERELATION_ID, $orderinvoicerelationId);
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
     * Set order_id
     * @param string $order_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInvoiceRelationInterface
     */
    public function setOrderId($order_id)
    {
        return $this->setData(self::ORDER_ID, $order_id);
    }

    /**
     * Get invoice_id
     * @return string
     */
    public function getInvoiceId()
    {
        return $this->getData(self::INVOICE_ID);
    }

    /**
     * Set invoice_id
     * @param string $invoice_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInvoiceRelationInterface
     */
    public function setInvoiceId($invoice_id)
    {
        return $this->setData(self::INVOICE_ID, $invoice_id);
    }

    public function setData($key, $value = null)
    {
        parent::setData($key, $value);
        return $this;
    }
}
