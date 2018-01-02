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

use Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentInterface;

class Shipment extends \Magento\Framework\Model\AbstractModel implements ShipmentInterface
{

    /**
     * @var string
     */
    const ENTITY = 'shipment';

    /**
     * @var string
     */
    protected $_eventPrefix = 'substitute_order_shipment';

    /**
     * @var string
     */
    protected $_eventObject = 'shipment';

    /*
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /*
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /*
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /*
     * @var \Dealer4Dealer\SubstituteOrders\Api\OrderAddressRepositoryInterface
     */
    protected $addressRepository;

    /*
     * @var \Dealer4Dealer\SubstituteOrders\Api\ShipmentItemRepositoryInterface
     */
    protected $itemRepository;

    /*
    * @var \Dealer4Dealer\SubstituteOrders\Api\AttachmentRepositoryInterface
    */
    protected $attachmentRepository;

    protected $_items = null;
    protected $_billingAddress = null;
    protected $_shippingAddress = null;
    protected $_tracking = null;
    protected $_additionalData = null;
    protected $_attachments = null;


    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Dealer4Dealer\SubstituteOrders\Api\ShipmentItemRepositoryInterface $shipmentItems,
        \Dealer4Dealer\SubstituteOrders\Api\OrderAddressRepositoryInterface $orderAddress,
        \Dealer4Dealer\SubstituteOrders\Api\AttachmentRepositoryInterface $attachmentRepository,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->itemRepository = $shipmentItems;
        $this->addressRepository = $orderAddress;
        $this->attachmentRepository = $attachmentRepository;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Dealer4Dealer\SubstituteOrders\Model\ResourceModel\Shipment');
    }


    /**
     * Saves Shipment object and related objects (address and items)
     * @return $this
     */
    public function save()
    {
        if ($this->_tracking) {
            $trackingData = [];
            foreach ($this->_tracking as $tracker) {
                $trackingData[] = $tracker->getArray();
            }

            $this->setData(self::TRACKING, json_encode($trackingData));
        }

        if ($this->_additionalData) {
            $data = [];
            foreach ($this->_additionalData as $value) {
                $data[$value->getKey()] = $value->getValue();
            }

            $this->setData(self::ADDITIONAL_DATA, json_encode($data));
        }


        if ($this->_shippingAddress) {
            if (!$this->getData(self::SHIPPING_ADDRESS_ID) && $this->_shippingAddress->getId() != $this->getData(self::SHIPPING_ADDRESS_ID)) {
                try {
                    $oldAddress = $this->addressRepository->getById($this->getData(self::SHIPPING_ADDRESS_ID));
                    $this->_shippingAddress->setData(
                        array_merge($oldAddress->getData(), $this->_shippingAddress->getData())
                    );
                } catch (\Exception $e) { // @codingStandardsIgnoreLine

                }
                $this->_shippingAddress->setId($this->getData(self::SHIPPING_ADDRESS_ID));
            }

            $this->_shippingAddress->save();
            $this->setData(self::SHIPPING_ADDRESS_ID, $this->_shippingAddress->getId());
        }

        if ($this->_billingAddress) {
            if (!$this->getData(self::BILLING_ADDRESS_ID) && $this->_billingAddress->getId() != $this->getData(self::BILLING_ADDRESS_ID)) {
                try {
                    $oldAddress = $this->addressRepository->getById($this->getData(self::BILLING_ADDRESS_ID));
                    $this->_billingAddress->setData(
                        array_merge($oldAddress->getData(), $this->_billingAddress->getData())
                    );
                } catch (\Exception $e) { // @codingStandardsIgnoreLine

                }
                $this->_billingAddress->setId($this->getData(self::BILLING_ADDRESS_ID));
            }

            $this->_billingAddress->save();
            $this->setData(self::BILLING_ADDRESS_ID, $this->_billingAddress->getId());
        }

        parent::save();

        if ($this->_items) {
            $oldItems = $this->itemRepository->getShipmentItems($this->getId());
            $oldSkus = [];
            $newSkus = [];
            foreach ($oldItems as $item) {
                $oldSkus[$item->getSku()] = $item;
            }

            foreach ($this->_items as $item) {
                $oldItem = isset($oldSkus[$item->getSku()]) ? $oldSkus[$item->getSku()] : null;

                if ($oldItem && $oldItem->getShipmentId() == $this->getId()) {
                    $item->setData(array_merge($oldItem->getData(), $item->getData()));
                    $item->setId($oldItem->getId());
                } else {
                    $item->setId(null);
                }

                $item->setShipmentId($this->getId());
                $item->save();

                $newSkus[$item->getSku()] = true;
            }

            foreach ($oldItems as $item) {
                if (!isset($newSkus[$item->getSku()])) {
                    $item->delete();
                }
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function delete()
    {
        if ($this->getShippingAddress()) {
            $this->getShippingAddress()->delete();
        }

        if ($this->getBillingAddress()) {
            $this->getBillingAddress()->delete();
        }

        if ($this->getItems()) {
            foreach ($this->getItems() as $item) {
                $item->delete();
            }
        }

        return parent::delete();
    }

    public function getRealOrderId()
    {
        $realOrderIdSetting = $this->scopeConfig->getValue(
            'substitute/general/real_order_id',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );

        if ($realOrderIdSetting == 'external') {
            $orderId = $this->getData('ext_order_id');
        } else {
            $orderId = $this->getData('magento_increment_id');
        }

        return $orderId ? $orderId : '-';
    }

    public function canShowBothIds()
    {
        return $this->scopeConfig->getValue(
            'substitute/general/show_both_ids',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }

    public function getAttachmentCollection()
    {
        return $this->attachmentRepository->getAttachmentsByEntityTypeIdentifier(
            $this->getShipmentId(),
            $this->getCustomerId(),
            self::ENTITY
        );
    }

    /**
     * @inheritDoc
     */
    public function getShippingAddress()
    {
        if (!$this->_shippingAddress) {
            try {
                $this->_shippingAddress = $this->addressRepository->getById($this->getData(self::SHIPPING_ADDRESS_ID));
            } catch (\Exception $e) { // @codingStandardsIgnoreLine

            }
        }
        return $this->_shippingAddress;
    }

    /**
     * @inheritDoc
     */
    public function setShippingAddress(\Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface $shipping_address)
    {
        $this->_shippingAddress = $shipping_address;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getBillingAddress()
    {
        if (!$this->_billingAddress) {
            try {
                $this->_billingAddress = $this->addressRepository->getById($this->getData(self::BILLING_ADDRESS_ID));
            } catch (\Exception $e) { // @codingStandardsIgnoreLine

            }
        }
        return $this->_billingAddress;
    }

    /**
     * @inheritDoc
     */
    public function setBillingAddress(\Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface $billing_address)
    {
        $this->_billingAddress = $billing_address;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setItems(array $items)
    {
        $this->_items = $items;
    }

    /**
     * @inheritDoc
     */
    public function getItems()
    {
        if (!$this->_items) {
            $this->_items = $this->itemRepository->getShipmentItems($this->getId());
        }

        return $this->_items;
    }


    /**
     * @inheritDoc
     */
    public function getTracking()
    {
        if ($this->_tracking == null) {
            $this->_tracking = [];

            if ($this->getData(self::TRACKING)) {
                $tracking = json_decode($this->getData(self::TRACKING), true);
                foreach ($tracking as $track) {
                    $this->_tracking[] = ShipmentTracking::createByArray($track);
                }
            }
        }
        return $this->_tracking;
    }

    /**
     * @inheritDoc
     */
    public function setTracking($tracking)
    {
        if (is_string($tracking)) {
            $tracking = json_decode($tracking);
        }
        return $this->_tracking = $tracking;
    }


    /**
     * @inheritDoc
     */
    public function getAdditionalData()
    {
        if ($this->_additionalData == null) {
            $this->_additionalData = [];

            if ($this->getData(self::ADDITIONAL_DATA)) {
                $data = json_decode($this->getData(self::ADDITIONAL_DATA), true);
                foreach ($data as $key => $value) {
                    $this->_additionalData[] = new AdditionalData($key, $value);
                }
            }
        }
        return $this->_additionalData;
    }

    /**
     * @inheritDoc
     */
    public function setAdditionalData($additional_data)
    {
        $this->_additionalData = $additional_data;
        return $this;
    }



    /* Standard getters and setters */


    /**
     * @inheritDoc
     */
    public function getShipmentId()
    {
        return $this->getData(self::SHIPMENT_ID);
    }

    /**
     * @inheritDoc
     */
    public function setShipmentId($shipmentId)
    {
        return $this->setData(self::SHIPMENT_ID, $shipmentId);
    }

    /**
     * @inheritDoc
     */
    public function getExtShipmentId()
    {
        return $this->getData(self::EXT_SHIPMENT_ID);
    }

    /**
     * @inheritDoc
     */
    public function setExtShipmentId($shipmentId)
    {
        return $this->setData(self::EXT_SHIPMENT_ID, $shipmentId);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerId($customer_id)
    {
        return $this->setData(self::CUSTOMER_ID, $customer_id);
    }

    /**
     * @inheritDoc
     */
    public function getOrderId()
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setOrderId($order_id)
    {
        return $this->setData(self::ORDER_ID, $order_id);
    }

    /**
     * @inheritDoc
     */
    public function getInvoiceId()
    {
        return $this->getData(self::INVOICE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setInvoiceId($invoice_id)
    {
        return $this->setData(self::INVOICE_ID, $invoice_id);
    }

    /**
     * @inheritDoc
     */
    public function getShipmentStatus()
    {
        return $this->getData(self::SHIPMENT_STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setShipmentStatus($shipment_status)
    {
        return $this->setData(self::SHIPMENT_STATUS, $shipment_status);
    }

    /**
     * @inheritDoc
     */
    public function getIncrementId()
    {
        return $this->getData(self::INCREMENT_ID);
    }

    /**
     * @inheritDoc
     */
    public function setIncrementId($increment_id)
    {
        return $this->setData(self::INCREMENT_ID, $increment_id);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt($created_at)
    {
        return $this->setData(self::CREATED_AT, $created_at);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt($updated_at)
    {
        return $this->setData(self::UPDATED_AT, $updated_at);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     */
    public function setAttachments(array $fileContent)
    {
        return $this->setData(self::FILE_CONTENT, $fileContent);
    }

    /**
     * @inheritDoc
     */
    public function getAttachments()
    {
        if ($this->_attachments == null) {
            $attachments = $this->attachmentRepository->getAttachmentsByEntityTypeIdentifier(
                $this->getShipmentId(),
                $this->getCustomerId(),
                self::ENTITY
            );

            $files = [];

            foreach ($attachments as $file) {
                $files[] = [
                    'file'=>$file->getFile(),
                    'attachment_id' => $file->getAttachmentId()
                ];
            }

            $this->_attachments = $files;
        }
        return $this->_attachments;
    }
}
