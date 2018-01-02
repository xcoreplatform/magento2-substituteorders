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

namespace Dealer4Dealer\SubstituteOrders\Controller\Order;

use Magento\Framework\App\RequestInterface;

class View extends \Magento\Framework\App\Action\Action
{
    /*
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /*
     * @var \Magento\Framework\Controller\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /*
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /*
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /*
     * @var \Dealer4Dealer\SubstituteOrders\Model\OrderFactory
     */
    protected $orderFactory;

    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Dealer4Dealer\SubstituteOrders\Model\OrderFactory $orderFactory
    ) {
        $this->registry = $registry;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->customerSession = $customerSession;
        $this->orderFactory = $orderFactory;
        parent::__construct($context);
    }

    /**
     * Check customer authentication
     *
     * @param RequestInterface $request
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function dispatch(RequestInterface $request)
    {
        $loginUrl = $this->_objectManager->get('Magento\Customer\Model\Url')->getLoginUrl();

        if (!$this->customerSession->authenticate($loginUrl)) {
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
        }
        return parent::dispatch($request);
    }

    public function execute()
    {
        $orderId = (int)$this->getRequest()->getParam('id');
        $magentoId = (int)$this->getRequest()->getParam('magento_id');

        if (empty($orderId) && empty($magentoId)) {
            $resultForward = $this->resultForwardFactory->create();
            return $resultForward->forward('noroute');
        }

        $customer = $this->customerSession->getCustomer();
        $order = null;
        if ($orderId) {
            $order = $this->orderFactory->create()->load($orderId);
        } elseif ($magentoId) {
            $order = $this->orderFactory->create()->load($magentoId, 'magento_order_id');
        }


        if ($order && $order->getId() && $this->canViewOrder($order, $customer)) {
            $this->registry->register('current_order', $order);

            $resultPage = $this->resultPageFactory->create();

            /** @var \Magento\Framework\View\Element\Html\Links $navigationBlock */
            $navigationBlock = $resultPage->getLayout()->getBlock('customer_account_navigation');
            if ($navigationBlock) {
                $navigationBlock->setActive('substitute/order');
            }

            return $resultPage;
        }

        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('noroute');
    }

    /**
     * @param $order
     * @param $customer
     * @return bool
     */
    public function canViewOrder($order, $customer)
    {
        $event = new \Magento\Framework\DataObject([
            'order' => $order,
            'customer' => $customer,
            'hasAccess' => $order->getData('magento_customer_id') == $customer->getId(),
        ]);

        $this->_eventManager->dispatch(
            'substituteorder_customer_has_order_access',
            ['event' => $event]
        );

        return $event->getData('hasAccess');
    }
}
