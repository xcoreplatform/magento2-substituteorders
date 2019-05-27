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

/**
 * Substitute Orders history block
 */
class Recent extends \Magento\Framework\View\Element\Template
{

    const DEFAULT_PAGE_SIZE = 10;

    /**
     * @var string
     */
    protected $_template = 'order/history.phtml';

    /*
     * @var \Dealer4Dealer\SubstituteOrders\Model\ResourceModel\Order\CollectionFactory
     */
    protected $orderCollectionFactory;

    /*
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;


    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Dealer4Dealer\SubstituteOrders\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->pageConfig->getTitle()->set(__('Orders'));

        $customerId = $this->customerSession->getCustomer()->getId();

        $collection = $this->orderCollectionFactory->create();
        $collection->setOrder('order_date')
            ->setOrder('magento_increment_id')
            ->addFieldToFilter('magento_customer_id', $customerId)
            ->setPageSize(5)
            ->load();

        $this->setOrders($collection);
    }

    public function getViewUrl($order)
    {
        return $this->getUrl('substitute/order/view', ['id' => $order->getId()]);
    }

    public function getReorderUrl($order)
    {
        return $this->getUrl('substitute/order/reorder', ['id' => $order->getId()]);
    }
}
