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

use \Magento\Framework\App\ObjectManager;
use \Magento\Sales\Model\ResourceModel\Order\CollectionFactoryInterface;

/**
 * Substitute Orders history block
 */
class History extends \Magento\Framework\View\Element\Template
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

        if ($this->getOrderCollection()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'dealer4dealer.orders.pager'
            );

            $pager->setAvailableLimit([10=>10,15=>15,20=>20])
                ->setShowPerPage(true)
                ->setCollection($this->getOrderCollection());

            $this->setChild('pager', $pager);
            $this->getOrderCollection()->load();
        }
    }

    public function getOrderCollection()
    {
        $customerId = $this->customerSession->getCustomer()->getId();

        $collection = $this->orderCollectionFactory->create();

        /* @var $collection \Dealer4Dealer\SubstituteOrders\Model\ResourceModel\Order\Collection */

        if ($this->getRequest()->getParam('date_from')) {
            $collection->addFieldToFilter(
                'order_date',
                ['gteq' => date('Y-m-d', strtotime($this->getRequest()->getParam('date_from'))) . ' 00:00:00']
            );
        }

        if ($this->getRequest()->getParam('date_to')) {
            $collection->addFieldToFilter(
                'order_date',
                ['lteq' => date('Y-m-d', strtotime($this->getRequest()->getParam('date_to'))) . ' 23:59:59']
            );
        }

        if ($this->getRequest()->getParam('q')) {
            $collection->addFieldToFilter(
                [
                    'po_number',
                    'magento_increment_id'
                ],
                [
                    ['like' => '%' . $this->getRequest()->getParam('q') . '%'],
                    ['like' => '%' . $this->getRequest()->getParam('q') . '%']
                ]
            );
        }

        $collection->setOrder('order_date', 'DESC')
            ->addFieldToFilter('magento_customer_id', $customerId)
            ->setPageSize($this->getPageSize())
            ->setCurPage($this->getCurrentPage());

        return $collection;
    }

    public function getCurrentPage()
    {
        return $this->getRequest()->getParam('p', 1);
    }

    public function getPageSize()
    {
        return $this->getRequest()->getParam('limit', self::DEFAULT_PAGE_SIZE);
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getViewUrl($order)
    {
        return $this->getUrl('*/*/view', ['id' => $order->getId()]);
    }

    public function getReorderUrl($order)
    {
        return $this->getUrl('*/*/reorder', ['id' => $order->getId()]);
    }
}
