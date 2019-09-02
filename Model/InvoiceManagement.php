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

class InvoiceManagement implements \Dealer4Dealer\SubstituteOrders\Api\InvoiceManagementInterface
{

    /**
     * @var InvoiceFactory
     */
    protected $invoiceFactory;

    /**
     * @var OrderAddressFactory
     */
    protected $addressFactory;

    /**
     * @var InvoiceItemFactory
     */
    protected $invoiceItemFactory;

    /*
    * @var \Dealer4Dealer\SubstituteOrders\Model\AttachmentRepository
    */
    protected $attachmentRepository;

    /*
    * @var \Dealer4Dealer\SubstituteOrders\Model\InvoiceRepository $invoiceRepository
    */
    protected $invoiceRepository;

    /** @var  */
    protected $orderFactory;

    public function __construct(
        \Dealer4Dealer\SubstituteOrders\Model\InvoiceFactory $invoiceFactory,
        \Dealer4Dealer\SubstituteOrders\Model\OrderAddressFactory $addressFactory,
        \Dealer4Dealer\SubstituteOrders\Model\InvoiceItemFactory $invoiceItemFactory,
        \Dealer4Dealer\SubstituteOrders\Model\OrderFactory $orderFactory,
        \Dealer4Dealer\SubstituteOrders\Model\AttachmentRepository $attachmentRepository,
        \Dealer4Dealer\SubstituteOrders\Model\InvoiceRepository $invoiceRepository
    ) {
        $this->invoiceFactory = $invoiceFactory;
        $this->addressFactory = $addressFactory;
        $this->invoiceItemFactory = $invoiceItemFactory;
        $this->attachmentRepository = $attachmentRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->orderFactory = $orderFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getInvoice($id)
    {
        $invoice = $this->invoiceFactory->create()->load($id);

        if (!$invoice->getId()) {
            throw new NoSuchEntityException(__('Invoice with id "%1" does not exist.', $id));
        }

        return $invoice;
    }

    /**
     * {@inheritdoc}
     */
    public function getInvoiceByExt($id)
    {
        $invoice = $this->invoiceFactory->create()->load($id, "ext_invoice_id");

        if (!$invoice->getId()) {
            throw new NoSuchEntityException(__('Invoice with ext_invoice_id "%1" does not exist.', $id));
        }

        return $invoice;
    }

    /**
     * {@inheritdoc}
     */
    public function getInvoiceByMagento($id)
    {
        $invoice = $this->invoiceFactory->create()->load($id, "magento_invoice_id");

        if (!$invoice->getId()) {
            throw new NoSuchEntityException(__('Invoice with magento_invoice_id "%1" does not exist.', $id));
        }

        return $invoice;
    }

    /**
     * {@inheritdoc}
     */
    public function getInvoiceByMagentoIncrementId($id)
    {
        $invoice = $this->invoiceFactory->create()->load($id, "magento_increment_id");

        if (!$invoice->getId()) {
            throw new NoSuchEntityException(__('Invoice with magento_increment_id "%1" does not exist.', $id));
        }

        return $invoice;
    }

    /**
     * {@inheritdoc}
     */
    public function postInvoice($invoice)
    {
        $invoice->setId(null);
        $invoice->save();

        $this->saveAttachment($invoice);

        return $invoice->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function putInvoice($invoice)
    {
        $oldInvoice = $this->invoiceFactory->create()->load($invoice->getId());

        if (!$oldInvoice->getId()) {
            return false;
        }

        $oldInvoice->setData(array_merge($oldInvoice->getData(), $invoice->getData()));
        $oldInvoice->setShippingAddress($invoice->getShippingAddress());
        $oldInvoice->setBillingAddress($invoice->getBillingAddress());
        $oldInvoice->setItems($invoice->getItems());
        $oldInvoice->setAdditionalData($invoice->getAdditionalData());

        $oldInvoice->save();

        $this->saveAttachment($oldInvoice);

        return $oldInvoice->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteInvoice($id)
    {
        $invoice = $this->invoiceFactory->create()->load($id);

        if (!$invoice->getId()) {
            throw new NoSuchEntityException(__('Order with id "%1" does not exist.', $id));
        }

        $invoice->delete();

        return true;
    }

    /**
     * @param $invoice
     */
    public function saveAttachment($invoice)
    {
        if (!empty($invoice->getFileContent())) {
            $this->attachmentRepository->saveAttachmentByEntityType(
                Invoice::ENTITY,
                $invoice->getInvoiceId(),
                $invoice->getMagentoCustomerId(),
                $invoice->getFileContent()
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    ) {
        return $this->invoiceRepository->getList($searchCriteria);
    }

    /**
     * Retrieve Shipments by the order increment id.
     * @param $id = Magento Order Increment Id
     * @return mixed
     */
    public function getInvoicesByOrderIncrementId($id)
    {
        // 1. get order by increment id.
        $order = $this->orderFactory->create();
        $order->load($id, "magento_increment_id");
        if (!$order->getId()) {
            throw new NoSuchEntityException(__('Order with increment_id "%1" does not exist.', $id));
        }

        // 2. get shipments.
        return $this->invoiceRepository->getInvoicesByOrder($order);
    }
}
