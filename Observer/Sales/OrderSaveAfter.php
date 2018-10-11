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
use Dealer4Dealer\SubstituteOrders\Model\OrderAddress;

class OrderSaveAfter implements \Magento\Framework\Event\ObserverInterface
{
    /** @var \Dealer4Dealer\SubstituteOrders\Api\OrderRepositoryInterface */
    protected $orderRepository;

    /** @var \Dealer4Dealer\SubstituteOrders\Api\OrderItemRepositoryInterface */
    protected $orderItemsRepository;

    /** @var \Dealer4Dealer\SubstituteOrders\Api\OrderAddressRepositoryInterface */
    protected $orderAddressesRepository;

    /** @var \Dealer4Dealer\SubstituteOrders\Model\OrderFactory */
    protected $orderFactory;

    /** @var \Dealer4Dealer\SubstituteOrders\Model\OrderAddressFactory */
    protected $addressFactory;

    /** @var \Dealer4Dealer\SubstituteOrders\Model\OrderItemFactory */
    protected $orderItemFactory;

    /**@var \Magento\Framework\App\Config\ScopeConfigInterface */
    protected $scopeConfig;

    /** @var \Magento\Customer\Api\CustomerRepositoryInterface  */
    protected $customerRepository;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Dealer4Dealer\SubstituteOrders\Model\OrderFactory $orderFactory,
        \Dealer4Dealer\SubstituteOrders\Model\OrderAddressFactory $addressFactory,
        \Dealer4Dealer\SubstituteOrders\Model\OrderItemFactory $orderItemFactory,
        \Dealer4Dealer\SubstituteOrders\Api\OrderRepositoryInterface $orders,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
    ) {
        $this->orderFactory = $orderFactory;
        $this->addressFactory = $addressFactory;
        $this->orderItemFactory = $orderItemFactory;

        $this->orderRepository = $orders;
        $this->scopeConfig = $scopeConfig;
        $this->customerRepository = $customerRepository;
    }

    /**
     * Execute observer
     *
     * @param Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getOrder();
        try {
            $substitute = $this->orderRepository->getByMagentoOrderId($order->getId());
        } catch (LocalizedException $e) {
            $substitute = $this->orderFactory->create();
            $substitute->setMagentoOrderId($order->getId());
        }

        $payment = $order->getPayment()->getMethodInstance();
        $payment->setStore($order->getStoreId());

        $substitute->setPoNumber($order->getPoNumber());
        $substitute->setMagentoCustomerId($order->getCustomerId());
        $substitute->setBaseTaxAmount($order->getBaseTaxAmount());
        $substitute->setBaseDiscountAmount($order->getBaseDiscountAmount());
        $substitute->setBaseShippingAmount($order->getBaseShippingAmount());
        $substitute->setBaseSubtotal($order->getBaseSubtotal());
        $substitute->setBaseGrandTotal($order->getBaseGrandTotal());
        $substitute->setShippingMethod($order->getShippingDescription());
        $substitute->setTaxAmount($order->getTaxAmount());
        $substitute->setDiscountAmount($order->getDiscountAmount());
        $substitute->setShippingAmount($order->getShippingAmount());
        $substitute->setSubtotal($order->getSubtotal());
        $substitute->setGrandTotal($order->getGrandTotal());
        $substitute->setOrderDate($order->getCreatedAt());
        $substitute->setState($order->getState());
        $substitute->setPaymentMethod($payment->getTitle());
        $substitute->setUpdatedAt($order->getUpdatedAt());
        $substitute->setMagentoIncrementId($order->getIncrementId());


        # Add billing address
        $substituteBillingAddress = $substitute->getBillingAddress();
        if (!$substituteBillingAddress) {
            $substituteBillingAddress = $this->addressFactory->create();
        }

        $billingAddressData = $order->getBillingAddress()->getData();
        $billingAddressData['country'] = $billingAddressData['country_id'];

        $substituteBillingAddress->setData(array_merge($substituteBillingAddress->getData(), $billingAddressData));
        $substitute->setBillingAddress($substituteBillingAddress);


        # Add shipping address
        $substituteShippingAddress = $substitute->getShippingAddress();
        if (!$substituteShippingAddress) {
            $substituteShippingAddress = $this->addressFactory->create();
        }

        $shippingAddressData = $order->getShippingAddress()->getData();
        $shippingAddressData['country'] = $shippingAddressData['country_id'];

        $substituteShippingAddress->setData(array_merge($substituteShippingAddress->getData(), $shippingAddressData));
        $substitute->setShippingAddress($substituteShippingAddress);

        $customer = $this->customerRepository->getById($order->getCustomerId());
        /** @var \Magento\Framework\Api\AttributeInterface */
        $externalCustomerIdAttribute = $customer->getCustomAttribute("external_customer_id");
        if ($externalCustomerIdAttribute->getValue() !== ''){
            $substitute->setExternalCustomerId($externalCustomerIdAttribute->getValue());
        }

        # Add order items
        $items = [];
        foreach ($order->getAllVisibleItems() as $item) {
            if (!empty($item->getData('parent_item'))) {
                continue;
            }

            $substituteItem = $this->orderItemFactory->create();
            $substituteItem->setData($item->getData());
            $substituteItem->setData('qty', $item->getData('qty_ordered'));

            $items[] = $substituteItem;
        }

        $substitute->setItems($items);

        # save order
        $substitute->save();
    }
}
