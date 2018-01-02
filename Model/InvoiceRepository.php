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

use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SortOrder;
use Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceSearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Dealer4Dealer\SubstituteOrders\Model\ResourceModel\Invoice\CollectionFactory as InvoiceCollectionFactory;
use Dealer4Dealer\SubstituteOrders\Model\ResourceModel\Invoice as ResourceInvoice;
use Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Dealer4Dealer\SubstituteOrders\Api\InvoiceRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;

class InvoiceRepository implements InvoiceRepositoryInterface
{

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var InvoiceInterfaceFactory
     */
    protected $dataInvoiceFactory;

    /**
     * @var InvoiceFactory
     */
    protected $invoiceFactory;

    /**
     * @var InvoiceSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var InvoiceCollectionFactory
     */
    protected $invoiceCollectionFactory;

    /**
     * @var ResourceInvoice
     */
    protected $resource;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;


    /**
     * @param ResourceInvoice $resource
     * @param InvoiceFactory $invoiceFactory
     * @param InvoiceInterfaceFactory $dataInvoiceFactory
     * @param InvoiceCollectionFactory $invoiceCollectionFactory
     * @param InvoiceSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceInvoice $resource,
        InvoiceFactory $invoiceFactory,
        InvoiceInterfaceFactory $dataInvoiceFactory,
        InvoiceCollectionFactory $invoiceCollectionFactory,
        InvoiceSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->resource = $resource;
        $this->invoiceFactory = $invoiceFactory;
        $this->invoiceCollectionFactory = $invoiceCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataInvoiceFactory = $dataInvoiceFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface $invoice
    ) {
        /* if (empty($invoice->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $invoice->setStoreId($storeId);
        } */
        try {
            $this->resource->save($invoice);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the invoice: %1',
                $exception->getMessage()
            ));
        }
        return $invoice;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($invoiceId)
    {
        $invoice = $this->invoiceFactory->create();
        $invoice->load($invoiceId);
        if (!$invoice->getId()) {
            throw new NoSuchEntityException(__('Invoice with id "%1" does not exist.', $invoiceId));
        }
        return $invoice;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->invoiceCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        $items = [];

        foreach ($collection as $invoiceModel) {
            $invoiceData = $this->dataInvoiceFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $invoiceData,
                $invoiceModel->getData(),
                'Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface'
            );

            $items[] = $invoiceData;
        }
        $searchResults->setItems($items);
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface $invoice
    ) {
        try {
            $this->resource->delete($invoice);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Invoice: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($invoiceId)
    {
        return $this->delete($this->getById($invoiceId));
    }

    public function getByMagentoInvoiceId($id)
    {
        $invoice = $this->invoiceFactory->create();
        $invoice->load($id, "magento_invoice_id");
        if (!$invoice->getId()) {
            throw new NoSuchEntityException(__('Order with id "%1" does not exist.', $id));
        }
        return $invoice;
    }
}
