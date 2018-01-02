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

use Dealer4Dealer\SubstituteOrders\Api\InvoiceItemRepositoryInterface;
use Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceItemSearchResultsInterfaceFactory;
use Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceItemInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Dealer4Dealer\SubstituteOrders\Model\InvoiceItemFactory;
use Dealer4Dealer\SubstituteOrders\Model\ResourceModel\InvoiceItem as ResourceInvoiceItem;
use Dealer4Dealer\SubstituteOrders\Model\ResourceModel\InvoiceItem\CollectionFactory as InvoiceItemCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;

class InvoiceItemRepository implements InvoiceitemRepositoryInterface
{

    /**
     * @var ResourceInvoiceItem
     */
    protected $resource;

    /**
     * @var \Dealer4Dealer\SubstituteOrders\Model\InvoiceItemFactory
     */
    protected $invoiceItemFactory;

    /**
     * @var InvoiceItemCollectionFactory
     */
    protected $invoiceItemCollectionFactory;

    /**
     * @var InvoiceItemSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var InvoiceItemInterfaceFactory
     */
    protected $dataInvoiceItemFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;
    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;


    /**
     * @param ResourceInvoiceItem $resource
     * @param InvoiceItemFactory $invoiceItemFactory
     * @param InvoiceItemInterfaceFactory $dataInvoiceItemFactory
     * @param InvoiceItemCollectionFactory $invoiceItemCollectionFactory
     * @param InvoiceItemSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceInvoiceItem $resource,
        InvoiceItemFactory $invoiceItemFactory,
        InvoiceItemInterfaceFactory $dataInvoiceItemFactory,
        InvoiceItemCollectionFactory $invoiceItemCollectionFactory,
        InvoiceItemSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder
    ) {
    
        $this->resource = $resource;
        $this->invoiceItemFactory = $invoiceItemFactory;
        $this->invoiceItemCollectionFactory = $invoiceItemCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataInvoiceItemFactory = $dataInvoiceItemFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceItemInterface $invoiceItem
    ) {
    
        /* if (empty($invoiceItem->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $invoiceItem->setStoreId($storeId);
        } */
        try {
            $this->resource->save($invoiceItem);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the invoiceItem: %1',
                $exception->getMessage()
            ));
        }
        return $invoiceItem;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($invoiceItemId)
    {
        $invoiceItem = $this->invoiceItemFactory->create();
        $invoiceItem->load($invoiceItemId);
        if (!$invoiceItem->getId()) {
            throw new NoSuchEntityException(__('Invoice_item with id "%1" does not exist.', $invoiceItemId));
        }
        return $invoiceItem;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
    
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->invoiceItemCollectionFactory->create();
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

        foreach ($collection as $invoiceItemModel) {
            $invoiceItemData = $this->dataInvoiceItemFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $invoiceItemData,
                $invoiceItemModel->getData(),
                'Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceItemInterface'
            );

            $items[] = $invoiceItemData;
        }
        $searchResults->setItems($items);
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceItemInterface $invoiceItem
    ) {
    
        try {
            $this->resource->delete($invoiceItem);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Invoice_item: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($invoiceItemId)
    {
        return $this->delete($this->getById($invoiceItemId));
    }

    public function getInvoiceItems($id)
    {
        $sortOrderOrderIds = $this->sortOrderBuilder
            ->setField('order_id')
            ->setDirection(SortOrder::SORT_DESC)
            ->create();

        $sortOrderItemIds = $this->sortOrderBuilder
            ->setField('invoiceitem_id')
            ->setDirection(SortOrder::SORT_DESC)
            ->create();

        $searchCriteria = $this->searchCriteriaBuilder
            ->addSortOrder($sortOrderOrderIds)
            ->addSortOrder($sortOrderItemIds)
            ->addFilter('invoice_id', $id, 'eq')
            ->create();

        $results = $this->getList($searchCriteria);

        return $results->getItems();
    }
}
