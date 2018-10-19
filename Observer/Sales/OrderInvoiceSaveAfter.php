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

class OrderInvoiceSaveAfter implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Dealer4Dealer\SubstituteOrders\Model\InvoiceFactory
     */
    protected $invoiceFactory;

    /**
     * @var \Dealer4Dealer\SubstituteOrders\Model\OrderAddressFactory
     */
    protected $addressFactory;

    /**
     * @var \Dealer4Dealer\SubstituteOrders\Model\InvoiceItemFactory
     */
    protected $invoiceItemFactory;

    /**
     * @var \Dealer4Dealer\SubstituteOrders\Api\InvoiceRepositoryInterface
     */
    protected $invoiceRepository;

    /**
     * @var \Dealer4Dealer\SubstituteOrders\Model\OrderFactory
     */
    protected $orderFactory;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Dealer4Dealer\SubstituteOrders\Model\InvoiceFactory $invoiceFactory,
        \Dealer4Dealer\SubstituteOrders\Model\OrderAddressFactory $addressFactory,
        \Dealer4Dealer\SubstituteOrders\Model\InvoiceItemFactory $invoiceItemFactory,
        \Dealer4Dealer\SubstituteOrders\Api\InvoiceRepositoryInterface $invoiceRepo,
        \Dealer4Dealer\SubstituteOrders\Model\OrderFactory $orderFactory
    ) {
        $this->logger = $logger;

        $this->invoiceFactory = $invoiceFactory;
        $this->addressFactory = $addressFactory;
        $this->invoiceItemFactory = $invoiceItemFactory;

        $this->invoiceRepository = $invoiceRepo;
        $this->orderFactory = $orderFactory;
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @throws \Exception
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $invoice = $observer->getInvoice();

        try {
            /** @var $substitute \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface */
            $substitute = $this->invoiceRepository->getByMagentoInvoiceId($invoice->getId());
        } catch (LocalizedException $e) {
            $substitute = $this->invoiceFactory->create();
            $substitute->setMagentoInvoiceId($invoice->getId());
        }

        $order = $this->orderFactory->create()->load($invoice->getOrderId(), 'magento_order_id');

        $substitute->setPoNumber($invoice->getPoNumber());
        $substitute->setMagentoCustomerId($invoice->getCustomerId());
        $substitute->setBaseTaxAmount($invoice->getBaseTaxAmount());
        $substitute->setBaseDiscountAmount($invoice->getBaseDiscountAmount());
        $substitute->setBaseShippingAmount($invoice->getBaseShippingAmount());
        $substitute->setBaseSubtotal($invoice->getBaseSubtotal());
        $substitute->setBaseGrandtotal($invoice->getBaseGrandTotal());
        $substitute->setTaxAmount($invoice->getTaxAmount());
        $substitute->setDiscountAmount($invoice->getDiscountAmount());
        $substitute->setShippingAmount($invoice->getShippingAmount());
        $substitute->setSubtotal($invoice->getSubtotal());
        $substitute->setGrandtotal($invoice->getGrandTotal());
        $substitute->setInvoiceDate($invoice->getCreatedAt());
        $substitute->setState($invoice->getState());
        $substitute->setUpdatedAt($invoice->getUpdatedAt());
        $substitute->setMagentoIncrementId($invoice->getIncrementId());


        # Add billing address
        $substituteBillingAddress = $substitute->getBillingAddress();
        if (!$substituteBillingAddress) {
            $substituteBillingAddress = $this->addressFactory->create();
        }

        $billingAddressData = $invoice->getBillingAddress()->getData();
        $billingAddressData['country'] = $billingAddressData['country_id'];

        $substituteBillingAddress->setData(array_merge($substituteBillingAddress->getData(), $billingAddressData));
        $substitute->setBillingAddress($substituteBillingAddress);


        # Add shipping address
        $substituteShippingAddress = $substitute->getShippingAddress();
        if (!$substituteShippingAddress) {
            $substituteShippingAddress = $this->addressFactory->create();
        }

        $shippingAddressData = $invoice->getShippingAddress()->getData();
        $shippingAddressData['country'] = $shippingAddressData['country_id'];

        $substituteShippingAddress->setData(array_merge($substituteShippingAddress->getData(), $shippingAddressData));
        $substitute->setShippingAddress($substituteShippingAddress);


        # Add order items
        $items = [];
        foreach ($invoice->getItems() as $item) {
            if (!empty($item->getData('parent_item'))) {
                continue;
            }

            $substituteItem = $this->invoiceItemFactory->create();
            $substituteItem->setData($item->getData());
            $substituteItem->setOrderId($order->getId());

            $items[] = $substituteItem;
        }

        $substitute->setItems($items);

        $orderIds = $substitute->getOrderIds();
        if (is_array($orderIds)) {
            $orderIds[] = $order->getId();
        } else {
            $orderIds = [$order->getId()];
        }

        $substitute->setOrderIds($orderIds);

        # save invoice
        $substitute->save();
    }
}
