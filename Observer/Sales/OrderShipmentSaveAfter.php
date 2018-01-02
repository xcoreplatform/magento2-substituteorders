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

namespace Dealer4Dealer\SubstituteOrders\Observer\Sales;

use Magento\Framework\Exception\LocalizedException;

class OrderShipmentSaveAfter implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Dealer4Dealer\SubstituteOrders\Model\ShipmentFactory
     */
    protected $shipmentFactory;

    /**
     * @var \Dealer4Dealer\SubstituteOrders\Model\OrderAddressFactory
     */
    protected $addressFactory;

    /**
     * @var \Dealer4Dealer\SubstituteOrders\Model\ShipmentItemFactory
     */
    protected $shipmentItemFactory;

    /**
     * @var \Dealer4Dealer\SubstituteOrders\Api\ShipmentRepositoryInterface
     */
    protected $shipmentRepo;

    /**
     * @var \Dealer4Dealer\SubstituteOrders\Model\OrderFactory
     */
    protected $orderFactory;

    public function __construct(
        \Dealer4Dealer\SubstituteOrders\Model\ShipmentFactory $shipmentFactory,
        \Dealer4Dealer\SubstituteOrders\Model\OrderAddressFactory $addressFactory,
        \Dealer4Dealer\SubstituteOrders\Model\ShipmentItemFactory $shipmentItemFactory,
        \Dealer4Dealer\SubstituteOrders\Api\ShipmentRepositoryInterface $shipmentRepo,
        \Dealer4Dealer\SubstituteOrders\Model\OrderFactory $orderFactory
    ) {
        $this->shipmentFactory = $shipmentFactory;
        $this->addressFactory = $addressFactory;
        $this->shipmentItemFactory = $shipmentItemFactory;

        $this->shipmentRepo = $shipmentRepo;
        $this->orderFactory = $orderFactory;
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {

        /* @var $shipment \Magento\Sales\Api\Data\ShipmentInterface */
        $shipment = $observer->getEvent()->getShipment();

        try {
            /* @var $substitute \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentInterface */
            $substitute = $this->shipmentRepo->getByIncrementId($shipment->getIncrementId());
        } catch (LocalizedException $e) {
            $substitute = $this->shipmentFactory->create();
        }

        $order = $this->orderFactory->create()->load($shipment->getOrderId(), 'magento_order_id');
        $substitute->setOrderId($order->getId());

        $substitute->setCustomerId($shipment->getCustomerId());
        $substitute->setIncrementId($shipment->getIncrementId());
        $substitute->setCreatedAt($shipment->getCreatedAt());
        $substitute->setShipmentStatus($shipment->getShipmentStatus());


        # Add trackers
        $trackers = [];
        foreach ($shipment->getTracks() as $track) {
            $trackers[] = new \Dealer4Dealer\SubstituteOrders\Model\ShipmentTracking(
                $track->getTitle(),
                $track->getTrackNumber()
            );
        }
        $substitute->setTracking($trackers);


        # Add billing address
        $substituteBillingAddress = $substitute->getBillingAddress();
        if (!$substituteBillingAddress) {
            $substituteBillingAddress = $this->addressFactory->create();
        }

        $billingAddressData = $shipment->getBillingAddress()->getData();
        $billingAddressData['country'] = $billingAddressData['country_id'];

        $substituteBillingAddress->setData(array_merge($substituteBillingAddress->getData(), $billingAddressData));
        $substitute->setBillingAddress($substituteBillingAddress);


        # Add shipping address
        $substituteShippingAddress = $substitute->getShippingAddress();
        if (!$substituteShippingAddress) {
            $substituteShippingAddress = $this->addressFactory->create();
        }

        $shippingAddressData = $shipment->getShippingAddress()->getData();
        $shippingAddressData['country'] = $shippingAddressData['country_id'];

        $substituteShippingAddress->setData(array_merge($substituteShippingAddress->getData(), $shippingAddressData));
        $substitute->setShippingAddress($substituteShippingAddress);


        # Add order items
        $items = [];
        foreach ($shipment->getItems() as $item) {
            if (!empty($item->getData('parent_item'))) {
                continue;
            }

            $substituteItem = $this->shipmentItemFactory->create();
            $substituteItem->setData($item->getData());

            $items[] = $substituteItem;
        }

        $substitute->setItems($items);


        # save order
        $substitute->save();
    }
}
