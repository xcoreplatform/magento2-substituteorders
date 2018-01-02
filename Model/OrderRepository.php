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
use Dealer4Dealer\SubstituteOrders\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Dealer4Dealer\SubstituteOrders\Api\OrderRepositoryInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Dealer4Dealer\SubstituteOrders\Model\ResourceModel\Order as ResourceOrder;
use Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterfaceFactory;
use Dealer4Dealer\SubstituteOrders\Api\Data\OrderSearchResultsInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

class OrderRepository implements OrderRepositoryInterface
{

    /*
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /*
     * @var OrderCollectionFactory
     */
    protected $orderCollectionFactory;

    /*
     * @var OrderInterfaceFactory
     */
    protected $dataOrderFactory;

    /*
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /*
     * @var OrderFactory
     */
    protected $orderFactory;

    /*
     * @var ResourceOrder
     */
    protected $resource;

    /*
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /*
     * @var OrderSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /*
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;


    /**
     * @param ResourceOrder $resource
     * @param OrderFactory $orderFactory
     * @param OrderInterfaceFactory $dataOrderFactory
     * @param OrderCollectionFactory $orderCollectionFactory
     * @param OrderSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceOrder $resource,
        OrderFactory $orderFactory,
        OrderInterfaceFactory $dataOrderFactory,
        OrderCollectionFactory $orderCollectionFactory,
        OrderSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->resource = $resource;
        $this->orderFactory = $orderFactory;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataOrderFactory = $dataOrderFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface $order
    ) {
        /* if (empty($order->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $order->setStoreId($storeId);
        } */
        try {
            $this->resource->save($order);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the order: %1',
                $exception->getMessage()
            ));
        }
        return $order;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($orderId)
    {
        $order = $this->orderFactory->create();
        $order->load($orderId);
        if (!$order->getId()) {
            throw new NoSuchEntityException(__('Order with id "%1" does not exist.', $orderId));
        }
        return $order;
    }

    /**
     * {@inheritdoc}
     */
    public function getByMagentoOrderId($id)
    {
        $order = $this->orderFactory->create();
        $order->load($id, "magento_order_id");
        if (!$order->getId()) {
            throw new NoSuchEntityException(__('Order with id "%1" does not exist.', $id));
        }
        return $order;
    }

    /**
     * {@inheritdoc}
     */
    public function getByExtOrderId($id)
    {
        $order = $this->orderFactory->create();
        $order->load($id, "ext_order_id");
        if (!$order->getId()) {
            throw new NoSuchEntityException(__('External Order with id "%1" does not exist.', $id));
        }
        return $order;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->orderCollectionFactory->create();
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

        foreach ($collection as $orderModel) {
            $orderData = $this->dataOrderFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $orderData,
                $orderModel->getData(),
                'Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface'
            );
            $items[] = $orderData;
        }
        $searchResults->setItems($items);
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface $order
    ) {
        try {
            $this->resource->delete($order);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Order: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($orderId)
    {
        return $this->delete($this->getById($orderId));
    }

    public function getOrdersByInvoice($invoice)
    {
        $searchCriteria = $this->searchCriteriaBuilder->addFilter('invoice_id', $invoice->getId(), 'eq')->create();
        $results = $this->getList($searchCriteria);

        return $results->getItems();
    }

    public function getOrders($ids)
    {
        $searchCriteria = $this->searchCriteriaBuilder->addFilter('order_id', $ids, 'in')->create();
        $results = $this->getList($searchCriteria);

        return $results->getItems();
    }
}
