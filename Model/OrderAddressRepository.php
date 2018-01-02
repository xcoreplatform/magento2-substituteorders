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

use Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Reflection\DataObjectProcessor;
use Dealer4Dealer\SubstituteOrders\Model\ResourceModel\OrderAddress as ResourceOrderAddress;
use Magento\Framework\Api\SortOrder;
use Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressSearchResultsInterfaceFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Dealer4Dealer\SubstituteOrders\Model\ResourceModel\OrderAddress\CollectionFactory as OrderAddressCollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Dealer4Dealer\SubstituteOrders\Api\OrderAddressRepositoryInterface;

class OrderAddressRepository implements OrderAddressRepositoryInterface
{

    /*
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /*
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /*
     * @var OrderAddressCollectionFactory
     */
    protected $orderAddressCollectionFactory;

    /*
     * @var OrderAddressFactory
     */
    protected $orderAddressFactory;

    /*
     * @var ResourceOrderAddress
     */
    protected $resource;

    /*
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /*
     * @var OrderAddressInterfaceFactory
     */
    protected $dataOrderAddressFactory;

    /*
     * @var OrderAddressSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;


    /**
     * @param ResourceOrderAddress $resource
     * @param OrderAddressFactory $orderAddressFactory
     * @param OrderAddressInterfaceFactory $dataOrderAddressFactory
     * @param OrderAddressCollectionFactory $orderAddressCollectionFactory
     * @param OrderAddressSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceOrderAddress $resource,
        OrderAddressFactory $orderAddressFactory,
        OrderAddressInterfaceFactory $dataOrderAddressFactory,
        OrderAddressCollectionFactory $orderAddressCollectionFactory,
        OrderAddressSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->orderAddressFactory = $orderAddressFactory;
        $this->orderAddressCollectionFactory = $orderAddressCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataOrderAddressFactory = $dataOrderAddressFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface $orderAddress
    ) {
        /* if (empty($orderAddress->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $orderAddress->setStoreId($storeId);
        } */
        try {
            $this->resource->save($orderAddress);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the orderAddress: %1',
                $exception->getMessage()
            ));
        }
        return $orderAddress;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($orderAddressId)
    {
        $orderAddress = $this->orderAddressFactory->create();
        $orderAddress->load($orderAddressId);
        if (!$orderAddress->getId()) {
            throw new NoSuchEntityException(__('OrderAddress with id "%1" does not exist.', $orderAddressId));
        }
        return $orderAddress;
    }

    /**
     * {@inheritdoc}
     */
    public function saveByAddress(
        \Magento\Sales\Api\Data\OrderAddressInterface $address
    ) {
        $orderAddress = $this->orderAddressFactory->create();
        $orderAddress->setName(implode(" ", array_filter([
            $address->getFirstname(),
            $address->getMiddlename(),
            $address->getLastname(),
        ])));
        $orderAddress->setCompany($address->getCompany());
        // TODO: Make full address
        return $this->save($orderAddress);
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->orderAddressCollectionFactory->create();
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

        foreach ($collection as $orderAddressModel) {
            $orderAddressData = $this->dataOrderAddressFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $orderAddressData,
                $orderAddressModel->getData(),
                'Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface'
            );
            $items[] = $this->dataObjectProcessor->buildOutputDataArray(
                $orderAddressData,
                'Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface'
            );
        }
        $searchResults->setItems($items);
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface $orderAddress
    ) {
        try {
            $this->resource->delete($orderAddress);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the OrderAddress: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($orderAddressId)
    {
        return $this->delete($this->getById($orderAddressId));
    }
}
