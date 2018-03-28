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

use Magento\Framework\Exception\NoSuchEntityException;

class OrderManagement implements \Dealer4Dealer\SubstituteOrders\Api\OrderManagementInterface
{
    /*
     * @var \Dealer4Dealer\SubstituteOrders\Model\OrderFactory
     */
    protected $orderFactory;

    /*
     * @var \Dealer4Dealer\SubstituteOrders\Model\OrderAddressFactory
     */
    protected $addressFactory;

    /*
     * @var \Dealer4Dealer\SubstituteOrders\Model\OrderItemFactory
     */
    protected $orderItemFactory;

    /*
     * @var \Dealer4Dealer\SubstituteOrders\Model\AttachmentRepository
     */
    protected $attachmentRepository;

    /*
     * @var \Dealer4Dealer\SubstituteOrders\Model\OrderRepository
     */
    protected $orderRepository;

    /**
     * OrderManagement constructor.
     * @param OrderFactory $orderFactory
     * @param OrderAddressFactory $addressFactory
     * @param OrderItemFactory $orderItemFactory
     * @param AttachmentRepository $attachmentRepository
     */
    public function __construct(
        \Dealer4Dealer\SubstituteOrders\Model\OrderFactory $orderFactory,
        \Dealer4Dealer\SubstituteOrders\Model\OrderAddressFactory $addressFactory,
        \Dealer4Dealer\SubstituteOrders\Model\OrderItemFactory $orderItemFactory,
        \Dealer4Dealer\SubstituteOrders\Model\AttachmentRepository $attachmentRepository,
        \Dealer4Dealer\SubstituteOrders\Model\OrderRepository $orderRepository
    ) {
    
        $this->orderFactory = $orderFactory;
        $this->addressFactory = $addressFactory;
        $this->orderItemFactory = $orderItemFactory;
        $this->attachmentRepository = $attachmentRepository;
        $this->orderRepository = $orderRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function postOrder($order)
    {
        $order->setId(null);
        $order->save();

        $this->saveAttachment($order);

        return $order->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder($id)
    {
        $order = $this->orderFactory->create()->load($id);

        if (!$order->getId()) {
            throw new NoSuchEntityException(__('Order with id "%1" does not exist.', $id));
        }

        return $order;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderByMagento($id)
    {
        $order = $this->orderFactory->create()->load($id, "magento_order_id");

        if (!$order->getId()) {
            throw new NoSuchEntityException(__('Order with magento_order_id "%1" does not exist.', $id));
        }

        return $order;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderByMagentoIncrementId($id)
    {
        $order = $this->orderFactory->create()->load($id, "magento_increment_id");

        if (!$order->getId()) {
            throw new NoSuchEntityException(__('Order with magento_increment_id "%1" does not exist.', $id));
        }

        return $order;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderByExt($id)
    {
        $order = $this->orderFactory->create()->load($id, "ext_order_id");

        if (!$order->getId()) {
            throw new NoSuchEntityException(__('Order with ext_order_id "%1" does not exist.', $id));
        }

        return $order;
    }

    /**
     * {@inheritdoc}
     */
    public function putOrder($order)
    {
        $oldOrder = $this->orderFactory->create()->load($order->getId());

        if (!$oldOrder->getId()) {
            return false;
        }

        $oldOrder->setData(array_merge($oldOrder->getData(), $order->getData()));

        if ($shippingAddress = $order->getShippingAddress()) {
            $oldOrder->setShippingAddress($shippingAddress);
        }

        if ($billingAddress = $order->getBillingAddress()) {
            $oldOrder->setBillingAddress($billingAddress);
        }

        $oldOrder->setItems($order->getItems());
        $oldOrder->setAdditionalData($order->getAdditionalData());

        $oldOrder->save();

        $this->saveAttachment($oldOrder);

        return $oldOrder->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteOrder($id)
    {
        $order = $this->orderFactory->create()->load($id);

        if (!$order->getId()) {
            throw new NoSuchEntityException(__('Order with id "%1" does not exist.', $id));
        }

        $order->delete();

        return true;
    }

    /**
     * @param $order
     */
    public function saveAttachment($order)
    {
        if (!empty($order->getFileContent())) {
            $this->attachmentRepository->saveAttachmentByEntityType(
                Order::ENTITY,
                $order->getOrderId(),
                $order->getMagentoCustomerId(),
                $order->getFileContent()
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    ) {
        return $this->orderRepository->getList($searchCriteria);
    }
}
