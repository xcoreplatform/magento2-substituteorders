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

interface InvoiceRepositoryInterface
{
    /**
     * Save Invoice
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface $invoice
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface $invoice
    );

    /**
     * Retrieve Invoice
     * @param string $invoiceId
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($invoiceId);

    /**
     * Retrieve Invoice matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Invoice
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface $invoice
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface $invoice
    );

    /**
     * Delete Invoice by ID
     * @param string $invoiceId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($invoiceId);

    /**
     * @param Data\OrderInterface $order
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceSearchResultsInterface
     */
    public function getInvoicesByOrder(\Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface $order);
}
