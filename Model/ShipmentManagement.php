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

class ShipmentManagement
{

    /*
     * @var \Dealer4Dealer\SubstituteOrders\Model\ShipmentFactory
     */
    protected $shipmentFactory;

    /*
     * @var \Dealer4Dealer\SubstituteOrders\Model\OrderAddressFactory
     */
    protected $addressFactory;

    /*
    * @var \Dealer4Dealer\SubstituteOrders\Model\AttachmentRepository
    */
    protected $attachmentRepository;

    public function __construct(
        \Dealer4Dealer\SubstituteOrders\Model\ShipmentFactory $shipmentFactory,
        \Dealer4Dealer\SubstituteOrders\Model\OrderAddressFactory $addressFactory,
        \Dealer4Dealer\SubstituteOrders\Model\AttachmentRepository $attachmentRepository
    ) {
        $this->shipmentFactory = $shipmentFactory;
        $this->addressFactory = $addressFactory;
        $this->attachmentRepository = $attachmentRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getShipment($id)
    {
        $shipment = $this->shipmentFactory->create()->load($id);

        if (!$shipment->getId()) {
            throw new NoSuchEntityException(__('Shipment with id "%1" does not exist.', $id));
        }

        return $shipment;
    }

    /**
     * {@inheritdoc}
     */
    public function postShipment($shipment)
    {
        $shipment->setId(null);
        $shipment->save();

        $this->saveAttachment($shipment);

        return $shipment->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function putShipment($shipment)
    {
        $oldShipment = $this->shipmentFactory->create()->load($shipment->getId());

        if (!$oldShipment->getId()) {
            return false;
        }

        $oldShipment->setData(array_merge($oldShipment->getData(), $shipment->getData()));
        $oldShipment->setShippingAddress($shipment->getShippingAddress());
        $oldShipment->setBillingAddress($shipment->getBillingAddress());
        $oldShipment->setItems($shipment->getItems());
        $oldShipment->setTracking($shipment->getTracking());
        $oldShipment->setAdditionalData($shipment->getAdditionalData());

        $oldShipment->save();

        $this->saveAttachment($oldShipment);

        return $oldShipment->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteShipment($id)
    {
        $shipment = $this->shipmentFactory->create()->load($id);

        if (!$shipment->getId()) {
            throw new NoSuchEntityException(__('Shipment with id "%1" does not exist.', $id));
        }

        $shipment->delete();

        return true;
    }

    /**
     * @param $shipment
     */
    public function saveAttachment($shipment)
    {
        if (!empty($shipment->getFileContent())) {
            $this->attachmentRepository->saveAttachmentByEntityType(
                Shipment::ENTITY,
                $shipment->getShipmentId(),
                $shipment->getMagentoCustomerId(),
                $shipment->getFileContent()
            );
        }
    }
}
