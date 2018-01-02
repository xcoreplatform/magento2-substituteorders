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

use Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentItemInterfaceFactory;
use Dealer4Dealer\SubstituteOrders\Model\ResourceModel\ShipmentItem as ResourceShipmentItem;
use Magento\Framework\Reflection\DataObjectProcessor;
use Dealer4Dealer\SubstituteOrders\Api\ShipmentItemRepositoryInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Dealer4Dealer\SubstituteOrders\Model\ResourceModel\ShipmentItem\CollectionFactory as ShipmentItemCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentItemSearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Store\Model\StoreManagerInterface;
use \Magento\Framework\Api\SearchCriteriaBuilder;

class ShipmentItemRepository implements ShipmentItemRepositoryInterface
{

    /*
     * @var ShipmentItemInterfaceFactory
     */
    protected $dataShipmentItemFactory;

    /*
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /*
     * @var ShipmentItemSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /*
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /*
     * @var ResourceShipmentItem
     */
    protected $resource;

    /*
     * @var ShipmentItemCollectionFactory
     */
    protected $shipmentItemCollectionFactory;

    /*
     * @var
     */
    protected $dataObjectProcessor;

    /*
     * @var ShipmentItemFactory
     */
    protected $shipmentItemFactory;

    /*
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;


    /**
     * @param ResourceShipmentItem $resource
     * @param ShipmentItemFactory $shipmentItemFactory
     * @param ShipmentItemInterfaceFactory $dataShipmentItemFactory
     * @param ShipmentItemCollectionFactory $shipmentItemCollectionFactory
     * @param ShipmentItemSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceShipmentItem $resource,
        ShipmentItemFactory $shipmentItemFactory,
        ShipmentItemInterfaceFactory $dataShipmentItemFactory,
        ShipmentItemCollectionFactory $shipmentItemCollectionFactory,
        ShipmentItemSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->resource = $resource;
        $this->shipmentItemFactory = $shipmentItemFactory;
        $this->shipmentItemCollectionFactory = $shipmentItemCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataShipmentItemFactory = $dataShipmentItemFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentItemInterface $shipmentItem
    ) {
        /* if (empty($shipmentItem->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $shipmentItem->setStoreId($storeId);
        } */
        try {
            $this->resource->save($shipmentItem);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the shipmentItem: %1',
                $exception->getMessage()
            ));
        }
        return $shipmentItem;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($shipmentItemId)
    {
        $shipmentItem = $this->shipmentItemFactory->create();
        $shipmentItem->load($shipmentItemId);
        if (!$shipmentItem->getId()) {
            throw new NoSuchEntityException(__('ShipmentItem with id "%1" does not exist.', $shipmentItemId));
        }
        return $shipmentItem;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->shipmentItemCollectionFactory->create();
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

        foreach ($collection as $shipmentItemModel) {
            $shipmentItemData = $this->dataShipmentItemFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $shipmentItemData,
                $shipmentItemModel->getData(),
                'Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentItemInterface'
            );
            $items[] = $shipmentItemData;
        }
        $searchResults->setItems($items);
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentItemInterface $shipmentItem
    ) {
        try {
            $this->resource->delete($shipmentItem);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the ShipmentItem: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($shipmentItemId)
    {
        return $this->delete($this->getById($shipmentItemId));
    }

    public function getShipmentItems($id)
    {
        $searchCriteria = $this->searchCriteriaBuilder->addFilter('shipment_id', $id, 'eq')->create();
        $results = $this->getList($searchCriteria);

        return $results->getItems();
    }
}
