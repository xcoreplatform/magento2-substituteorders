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

class Reorder extends \Magento\Framework\App\Action\Action
{

    /*
     * @var \Magento\Framework\Controller\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /*
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /*
     * @var \Dealer4Dealer\SubstituteOrders\Model\OrderFactory
     */
    protected $orderFactory;

    /*
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /*
     * @var \Magento\Checkout\Model\Cart
     */
    protected $cart;

    /*
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $orderRepository;


    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        \Dealer4Dealer\SubstituteOrders\Model\OrderFactory $orderFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        $this->customerSession = $customerSession;
        $this->orderFactory = $orderFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->cart = $cart;
        $this->orderRepository = $orderRepository;
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

        if (empty($orderId)) {
            $resultForward = $this->resultForwardFactory->create();
            return $resultForward->forward('noroute');
        }

        $subOrder = $this->orderFactory->create()->load($orderId);
        $incrementId = $subOrder->getMagentoOrderId();

        $magentoOrderFound = false;
        try{
            $magentoOrder = $this->orderRepository->get($incrementId);
            $magentoOrderFound = true;
        }
        catch(\Exception $exception){
        }

        // empty when order from External Platform
        if ($magentoOrderFound !== true) {
            return $this->reorderByProduct($orderId);
        } else {
            return $this->reorderByOrderItems($subOrder, $incrementId);
        }
    }

    /**
     * Reorder based on order items.
     *
     * @param $subOrder
     * @param $incrementId
     * @return
     */
    public function reorderByOrderItems($subOrder, $incrementId) {

        $customer = $this->customerSession->getCustomer();

        if ($subOrder && $subOrder->getId() && $this->canReOrder($subOrder, $customer)) {
            $order = $this->orderRepository->get($incrementId);

            foreach ($order->getItems() as $item) {
                $this->cart->addOrderItem($item);
            }

            $this->cart->save();

            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('checkout/cart');
        }

        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('noroute');
    }

    /**
     * Reorder based on product items.
     *
     * @param $orderId
     * @return
     */
    public function reorderByProduct($orderId) {

        $customer = $this->customerSession->getCustomer();
        $order = $this->orderFactory->create()->load($orderId);
        if ($order && $order->getId() && $this->canReOrder($order, $customer)) {
            $itemQty = [];
            foreach ($order->getItems() as $item) {
                $itemQty[$item['sku']] = $item['qty'];
            }
            $productCollection = $this->getProductCollection($itemQty);
            $errors = [];
            foreach ($productCollection as $product) {
                try {
                    if (isset($itemQty[$product->getSku()])) {
                        $result = $this->cart->addProduct($product, $itemQty[$product->getSku()]);
                    }
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $errors[] = $e->getMessage();
                } catch (\Exception $e) {
                    $errors[] = __("We can\'t add ({$product->getName()}) item to your shopping cart right now.");
                }
            }
            $this->cart->save();
            foreach ($errors as $error) {
                if ($this->customerSession->getUseNotice(true)) {
                    $this->messageManager->addNotice($error);
                } else {
                    $this->messageManager->addError($error);
                }
            }
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('checkout/cart');
        }
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('noroute');
    }

    /**
     * @param $order
     * @param $customer
     * @return bool
     */
    public function canReOrder($order, $customer)
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

    public function getProductCollection($skus)
    {
        $collection = $this->productCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('sku', ['in' => array_keys($skus)]);

        return $collection;
    }
}
