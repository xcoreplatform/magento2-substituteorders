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

use Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface;

class Invoice extends \Magento\Framework\Model\AbstractModel implements InvoiceInterface
{
    /**
     * @var string
     */
    const ENTITY = 'invoice';

    /**
     * @var string
     */
    protected $_eventPrefix = 'substitute_order_invoice';

    /**
     * @var string
     */
    protected $_eventObject = 'invoice';

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

    /**
     * @var ResourceModel\Order\CollectionFactory
     */
    protected $orderCollectionFactory;

    /**
     * @var \Dealer4Dealer\SubstituteOrders\Model\OrderInvoiceRelationFactory
     */
    protected $orderInvoiceRelationFactory;

    /**
     * @var \Dealer4Dealer\SubstituteOrders\Api\OrderAddressRepositoryInterface
     */
    protected $addressRepository;

    /**
     * @var \Dealer4Dealer\SubstituteOrders\Api\AttachmentRepositoryInterface
     */
    protected $attachmentRepository;

    /**
     * @var \Dealer4Dealer\SubstituteOrders\Api\InvoiceItemRepositoryInterface
     */
    protected $itemRepository;

    protected $_items = null;
    protected $_billingAddress = null;
    protected $_shippingAddress = null;
    protected $_additionalData = null;
    protected $_attachments = null;


    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Dealer4Dealer\SubstituteOrders\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Dealer4Dealer\SubstituteOrders\Model\OrderInvoiceRelationFactory $orderInvoiceRelationFactory,
        \Dealer4Dealer\SubstituteOrders\Api\InvoiceItemRepositoryInterface $orderItems,
        \Dealer4Dealer\SubstituteOrders\Api\OrderAddressRepositoryInterface $orderAddress,
        \Dealer4Dealer\SubstituteOrders\Api\AttachmentRepositoryInterface $attachmentRepository,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->orderInvoiceRelationFactory = $orderInvoiceRelationFactory;
        $this->itemRepository = $orderItems;
        $this->addressRepository = $orderAddress;
        $this->attachmentRepository = $attachmentRepository;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Dealer4Dealer\SubstituteOrders\Model\ResourceModel\Invoice');
    }

    public function save()
    {
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

        if ($this->getData(self::ORDER_IDS)) {
            $activeIds = [];
            $collection = $this->orderInvoiceRelationFactory->create()->getCollection()
                ->addFieldToFilter('invoice_id', $this->getId());

            foreach ($collection as $relation) {
                if (in_array($relation->getOrderId(), $this->getData(self::ORDER_IDS))) {
                    $activeIds[$relation->getOrderId()] = true;
                } else {
                    $relation->delete();
                }
            }

            foreach ($this->getData(self::ORDER_IDS) as $orderId) {
                if (!isset($activeIds[$orderId])) {
                    $relation = $this->orderInvoiceRelationFactory->create();
                    $relation->setData([
                        'order_id' => $orderId,
                        'invoice_id' => $this->getId()
                    ]);
                    $relation->save();
                }
            }
        }

        if ($this->_items) {
            $oldItems = $this->itemRepository->getInvoiceItems($this->getId());
            $oldSkus = [];
            $newSkus = [];
            foreach ($oldItems as $item) {
                $oldSkus[$item->getSku()] = $item;
            }

            foreach ($this->_items as $item) {
                $oldItem = isset($oldSkus[$item->getSku()]) ? $oldSkus[$item->getSku()] : null;

                if ($oldItem && $oldItem->getInvoiceId() == $this->getId()) {
                    $item->setData(array_merge($oldItem->getData(), $item->getData()));
                    $item->setId($oldItem->getId());
                } else {
                    $item->setId(null);
                }

                $item->setInvoiceId($this->getId());
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
            $orderId = $this->getData('ext_invoice_id');
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
            $this->getInvoiceId(),
            $this->getMagentoCustomerId(),
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
            $this->_items = $this->itemRepository->getInvoiceItems($this->getId());
        }

        return $this->_items;
    }

    /**
     * @return array
     */
    public function getAllItems()
    {
        $items = [];
        foreach ($this->getItemsCollection() as $item) {
            if (!$item->isDeleted()) {
                $items[] = $item;
            }
        }
        return $items;
    }

    // TODO make same ad original Magento Sales\Order\ model
    public function getItemsCollection()
    {
        return $this->getItems();
    }

    /**
     * Get all orders for invoice
     */
    public function getOrders()
    {
        if (!$this->getId()) {
            return [];
        }

        return $this->orderCollectionFactory->create()->filterByInvoice($this);
    }


    /* Standard getters and setters */


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
    public function setInvoiceId($invoiceId)
    {
        return $this->setData(self::INVOICE_ID, $invoiceId);
    }

    /**
     * @inheritDoc
     */
    public function getOrderIds()
    {
        if ($this->getData(self::ORDER_IDS)) {
            return $this->getData(self::ORDER_IDS);
        }

        $ids = [];
        foreach ($this->getOrders() as $order) {
            $ids[] = $order->getId();
        }

        return $ids;
    }

    /**
     * @inheritDoc
     */
    public function setOrderIds($orderIds)
    {
        return $this->setData(self::ORDER_IDS, array_unique($orderIds));
    }

    /**
     * @inheritDoc
     */
    public function getMagentoInvoiceId()
    {
        return $this->getData(self::MAGENTO_INVOICE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setMagentoInvoiceId($magento_invoice_id)
    {
        return $this->setData(self::MAGENTO_INVOICE_ID, $magento_invoice_id);
    }

    /**
     * @inheritDoc
     */
    public function getExtInvoiceId()
    {
        return $this->getData(self::EXT_INVOICE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setExtInvoiceId($ext_invoice_id)
    {
        return $this->setData(self::EXT_INVOICE_ID, $ext_invoice_id);
    }

    /**
     * @inheritDoc
     */
    public function getPoNumber()
    {
        return $this->getData(self::PO_NUMBER);
    }

    /**
     * @inheritDoc
     */
    public function setPoNumber($po_number)
    {
        return $this->setData(self::PO_NUMBER, $po_number);
    }

    /**
     * @inheritDoc
     */
    public function getMagentoCustomerId()
    {
        return $this->getData(self::MAGENTO_CUSTOMER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setMagentoCustomerId($magento_customer_id)
    {
        return $this->setData(self::MAGENTO_CUSTOMER_ID, $magento_customer_id);
    }

    /**
     * @inheritDoc
     */
    public function getBaseTaxAmount()
    {
        return $this->getData(self::BASE_TAX_AMOUNT);
    }

    /**
     * @inheritDoc
     */
    public function setBaseTaxAmount($base_tax_amount)
    {
        return $this->setData(self::BASE_TAX_AMOUNT, $base_tax_amount);
    }

    /**
     * @inheritDoc
     */
    public function getBaseDiscountAmount()
    {
        return $this->getData(self::BASE_DISCOUNT_AMOUNT);
    }

    /**
     * @inheritDoc
     */
    public function setBaseDiscountAmount($base_discount_amount)
    {
        return $this->setData(self::BASE_DISCOUNT_AMOUNT, $base_discount_amount);
    }

    /**
     * @inheritDoc
     */
    public function getBaseShippingAmount()
    {
        return $this->getData(self::BASE_SHIPPING_AMOUNT);
    }

    /**
     * @inheritDoc
     */
    public function setBaseShippingAmount($base_shipping_amount)
    {
        return $this->setData(self::BASE_SHIPPING_AMOUNT, $base_shipping_amount);
    }

    /**
     * @inheritDoc
     */
    public function getBaseSubtotal()
    {
        return $this->getData(self::BASE_SUBTOTAL);
    }

    /**
     * @inheritDoc
     */
    public function setBaseSubtotal($base_subtotal)
    {
        return $this->setData(self::BASE_SUBTOTAL, $base_subtotal);
    }

    /**
     * @inheritDoc
     */
    public function getBaseGrandtotal()
    {
        return $this->getData(self::BASE_GRANDTOTAL);
    }

    /**
     * @inheritDoc
     */
    public function setBaseGrandtotal($base_grandtotal)
    {
        return $this->setData(self::BASE_GRANDTOTAL, $base_grandtotal);
    }

    /**
     * @inheritDoc
     */
    public function getTaxAmount()
    {
        return $this->getData(self::TAX_AMOUNT);
    }

    /**
     * @inheritDoc
     */
    public function setTaxAmount($tax_amount)
    {
        return $this->setData(self::TAX_AMOUNT, $tax_amount);
    }

    /**
     * @inheritDoc
     */
    public function getDiscountAmount()
    {
        return $this->getData(self::DISCOUNT_AMOUNT);
    }

    /**
     * @inheritDoc
     */
    public function setDiscountAmount($discount_amount)
    {
        return $this->setData(self::DISCOUNT_AMOUNT, $discount_amount);
    }

    /**
     * @inheritDoc
     */
    public function getShippingAmount()
    {
        return $this->getData(self::SHIPPING_AMOUNT);
    }

    /**
     * @inheritDoc
     */
    public function setShippingAmount($shipping_amount)
    {
        return $this->setData(self::SHIPPING_AMOUNT, $shipping_amount);
    }

    /**
     * @inheritDoc
     */
    public function getSubtotal()
    {
        return $this->getData(self::SUBTOTAL);
    }

    /**
     * @inheritDoc
     */
    public function setSubtotal($subtotal)
    {
        return $this->setData(self::SUBTOTAL, $subtotal);
    }

    /**
     * @inheritDoc
     */
    public function getGrandtotal()
    {
        return $this->getData(self::GRANDTOTAL);
    }

    /**
     * @inheritDoc
     */
    public function setGrandtotal($grandtotal)
    {
        return $this->setData(self::GRANDTOTAL, $grandtotal);
    }

    /**
     * @inheritDoc
     */
    public function getInvoiceDate()
    {
        return $this->getData(self::INVOICE_DATE);
    }

    /**
     * @inheritDoc
     */
    public function setInvoiceDate($invoice_date)
    {
        return $this->setData(self::INVOICE_DATE, $invoice_date);
    }

    /**
     * @inheritDoc
     */
    public function getState()
    {
        return $this->getData(self::STATE);
    }

    /**
     * @inheritDoc
     */
    public function setState($state)
    {
        return $this->setData(self::STATE, $state);
    }

    /**
     * @inheritDoc
     */
    public function getMagentoIncrementId()
    {
        return $this->getData(self::MAGENTO_INCREMENT_ID);
    }

    /**
     * @inheritDoc
     */
    public function setMagentoIncrementId($incrementId)
    {
        return $this->setData(self::MAGENTO_INCREMENT_ID, $incrementId);
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
    public function setUpdatedAt($updated)
    {
        return $this->setData(self::UPDATED_AT, $updated);
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
                $this->getInvoiceId(),
                $this->getMagentoCustomerId(),
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
