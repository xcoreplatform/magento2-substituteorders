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

use Dealer4Dealer\SubstituteOrders\Model\ResourceModel\Shipment\CollectionFactory as ShipmentCollectionFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Reflection\DataObjectProcessor;
use Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentInterfaceFactory;
use Magento\Framework\Api\SortOrder;
use Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentSearchResultsInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Dealer4Dealer\SubstituteOrders\Model\ResourceModel\Shipment as ResourceShipment;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Dealer4Dealer\SubstituteOrders\Api\ShipmentRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;

class ShipmentRepository implements ShipmentRepositoryInterface
{

    /*
     * @var ShipmentFactory
     */
    protected $shipmentFactory;

    /*
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /*
     * @var ShipmentSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /*
     * @var ShipmentCollectionFactory
     */
    protected $shipmentCollectionFactory;

    /*
     * @var ShipmentInterfaceFactory
     */
    protected $dataShipmentFactory;

    /*
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /*
     * @var ResourceShipment
     */
    protected $resource;

    /*
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /*
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;


    /**
     * @param ResourceShipment $resource
     * @param ShipmentFactory $shipmentFactory
     * @param ShipmentInterfaceFactory $dataShipmentFactory
     * @param ShipmentCollectionFactory $shipmentCollectionFactory
     * @param ShipmentSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceShipment $resource,
        ShipmentFactory $shipmentFactory,
        ShipmentInterfaceFactory $dataShipmentFactory,
        ShipmentCollectionFactory $shipmentCollectionFactory,
        ShipmentSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->resource = $resource;
        $this->shipmentFactory = $shipmentFactory;
        $this->shipmentCollectionFactory = $shipmentCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataShipmentFactory = $dataShipmentFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentInterface $shipment
    ) {
        /* if (empty($shipment->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $shipment->setStoreId($storeId);
        } */
        try {
            $this->resource->save($shipment);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the shipment: %1',
                $exception->getMessage()
            ));
        }
        return $shipment;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($shipmentId)
    {
        $shipment = $this->shipmentFactory->create();
        $shipment->load($shipmentId);
        if (!$shipment->getId()) {
            throw new NoSuchEntityException(__('Shipment with id "%1" does not exist.', $shipmentId));
        }
        return $shipment;
    }

    public function getByIncrementId($incrementId)
    {
        $shipment = $this->shipmentFactory->create();
        $shipment->load($incrementId, "increment_id");
        if (!$shipment->getId()) {
            throw new NoSuchEntityException(__('Shipment with increment Id "%1" does not exist.', $incrementId));
        }
        return $shipment;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->shipmentCollectionFactory->create();
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

        foreach ($collection as $shipmentModel) {
            $shipmentData = $this->dataShipmentFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $shipmentData,
                $shipmentModel->getData(),
                'Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentInterface'
            );
            $items[] = $shipmentData;
        }
        $searchResults->setItems($items);
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentInterface $shipment
    ) {
        try {
            $this->resource->delete($shipment);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Shipment: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($shipmentId)
    {
        return $this->delete($this->getById($shipmentId));
    }

    public function getShipmentsByOrder(\Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface $order)
    {
        $searchCriteria = $this->searchCriteriaBuilder->addFilter('order_id', $order->getId(), 'eq')->create();
        $results = $this->getList($searchCriteria);

        return $results->getItems();
    }
}
