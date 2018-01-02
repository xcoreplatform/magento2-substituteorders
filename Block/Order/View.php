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

namespace Dealer4Dealer\SubstituteOrders\Block\Order;

class View extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template = 'order/view.phtml';

    /*
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /*
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->pageConfig->getTitle()->set(__('Order # %1', $this->getOrder()->getRealOrderId()));
    }

    /**
     * @return \Dealer4Dealer\SubstituteOrders\Model\Order
     */
    public function getOrder()
    {
        return $this->registry->registry('current_order');
    }

    public function getReorderUrl($order)
    {
        return $this->getUrl('*/*/reorder', ['id' => $order->getId()]);
    }

    public function getPrintUrl($order)
    {
        return $this->getUrl('*/*/printorder', ['id' => $order->getId()]);
    }

    public function getInvoiceUrl()
    {
        return $this->getUrl('*/*/invoice', ['id' => $this->getOrder()->getId()]);
    }

    public function getShipmentUrl()
    {
        return $this->getUrl('*/*/shipment', ['id' => $this->getOrder()->getId()]);
    }
}
