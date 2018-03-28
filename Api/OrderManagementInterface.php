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

interface OrderManagementInterface
{


    /**
     * POST for order api
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface $order
     * @return int
     */
    public function postOrder($order);

    /**
     * GET order by order_id
     * @param string $id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    public function getOrder($id);

    /**
     * GET order by magento_order_id
     * @param string $id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    public function getOrderByMagento($id);

    /**
     * GET order by ext_order_id
     * @param string $id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    public function getOrderByExt($id);

    /**
     * GET order by magento_increment_id
     * @param string $id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    public function getOrderByMagentoIncrementId($id);

    /**
     * PUT for order api
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface $order
     * @return int
     */
    public function putOrder($order);

    /**
     * DELETE for order api
     * @param string $id
     * @return boolean
     */
    public function deleteOrder($id);

    /**
     * Retrieve Order matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );
}
