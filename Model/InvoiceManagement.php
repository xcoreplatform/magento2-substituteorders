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

class InvoiceManagement
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

    public function __construct(
        \Dealer4Dealer\SubstituteOrders\Model\InvoiceFactory $invoiceFactory,
        \Dealer4Dealer\SubstituteOrders\Model\OrderAddressFactory $addressFactory,
        \Dealer4Dealer\SubstituteOrders\Model\InvoiceItemFactory $invoiceItemFactory,
        \Dealer4Dealer\SubstituteOrders\Model\AttachmentRepository $attachmentRepository
    ) {
        $this->invoiceFactory = $invoiceFactory;
        $this->addressFactory = $addressFactory;
        $this->invoiceItemFactory = $invoiceItemFactory;
        $this->attachmentRepository = $attachmentRepository;
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
}
