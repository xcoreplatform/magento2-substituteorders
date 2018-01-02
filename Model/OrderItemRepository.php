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
use Magento\Framework\Api\DataObjectHelper;
use Dealer4Dealer\SubstituteOrders\Model\ResourceModel\OrderItem as ResourceOrderItem;
use Magento\Store\Model\StoreManagerInterface;
use Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemSearchResultsInterfaceFactory;
use Magento\Framework\Api\SortOrder;
use Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Dealer4Dealer\SubstituteOrders\Model\ResourceModel\OrderItem\CollectionFactory as OrderItemCollectionFactory;
use Dealer4Dealer\SubstituteOrders\Api\OrderItemRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

class OrderItemRepository implements OrderItemRepositoryInterface
{
    /*
     * @var OrderItemFactory
     */
    protected $orderItemFactory;

    /*
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /*
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /*
     * @var OrderItemCollectionFactory
     */
    protected $orderItemCollectionFactory;

    /*
     * @var ResourceOrderItem
     */
    protected $resource;

    /*
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /*
     * @var OrderItemSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /*
     * @var OrderItemInterfaceFactory
     */
    protected $dataOrderItemFactory;

    /*
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;


    /**
     * @param ResourceOrderItem $resource
     * @param OrderItemFactory $orderItemFactory
     * @param OrderItemInterfaceFactory $dataOrderItemFactory
     * @param OrderItemCollectionFactory $orderItemCollectionFactory
     * @param OrderItemSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceOrderItem $resource,
        OrderItemFactory $orderItemFactory,
        OrderItemInterfaceFactory $dataOrderItemFactory,
        OrderItemCollectionFactory $orderItemCollectionFactory,
        OrderItemSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->resource = $resource;
        $this->orderItemFactory = $orderItemFactory;
        $this->orderItemCollectionFactory = $orderItemCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataOrderItemFactory = $dataOrderItemFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface $orderItem
    ) {
        /* if (empty($orderItem->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $orderItem->setStoreId($storeId);
        } */
        try {
            $this->resource->save($orderItem);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the orderItem: %1',
                $exception->getMessage()
            ));
        }
        return $orderItem;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($orderItemId)
    {
        $orderItem = $this->orderItemFactory->create();
        $orderItem->load($orderItemId);
        if (!$orderItem->getId()) {
            throw new NoSuchEntityException(__('OrderItem with id "%1" does not exist.', $orderItemId));
        }
        return $orderItem;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->orderItemCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
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

        foreach ($collection as $orderItemModel) {
            $orderItemData = $this->dataOrderItemFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $orderItemData,
                $orderItemModel->getData(),
                'Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface'
            );

            $items[] = $orderItemData;
        }
        $searchResults->setItems($items);
        return $searchResults;
    }

    public function getOrderItems($orderId)
    {
        $searchCriteria = $this->searchCriteriaBuilder->addFilter('order_id', $orderId, 'eq')->create();
        $results = $this->getList($searchCriteria);

        return $results->getItems();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Dealer4Dealer\SubstituteOrders\Api\Data\OrderItemInterface $orderItem
    ) {
        try {
            $this->resource->delete($orderItem);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the OrderItem: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($orderItemId)
    {
        return $this->delete($this->getById($orderItemId));
    }
}
