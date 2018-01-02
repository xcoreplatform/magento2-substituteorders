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

namespace Dealer4Dealer\SubstituteOrders\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface OrderRepositoryInterface
{
    /**
     * Save Order
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface $order
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface $order
    );

    /**
     * Retrieve Order
     * @param string $orderId
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($orderId);
    
    /**
     * Retrieve an order by Magento id
     * @param string $id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByMagentoOrderId($id);

    /**
     * Retrieve an order by ext_order_id
     * @param string $id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByExtOrderId($id);

    /**
     * Retrieve Order matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Order
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface $order
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface $order
    );

    /**
     * Delete Order by ID
     * @param string $orderId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($orderId);
}
