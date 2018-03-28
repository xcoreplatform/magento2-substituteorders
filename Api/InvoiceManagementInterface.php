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

interface InvoiceManagementInterface
{
    /**
     * GET Invoice by invoice_id
     * @param string $id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function getInvoice($id);

    /**
     * GET Invoice by ext_invoice_id
     * @param string $id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function getInvoiceByExt($id);

    /**
     * GET Invoice by magento_invoice_id
     * @param string $id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function getInvoiceByMagento($id);

    /**
     * GET Invoice by magento_increment_id
     * @param string $id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface
     */
    public function getInvoiceByMagentoIncrementId($id);

    /**
     * POST for Invoice api
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface $invoice
     * @return int
     */
    public function postInvoice($invoice);

    /**
     * PUT for Invoice api
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface $invoice
     * @return int
     */
    public function putInvoice($invoice);

    /**
     * DELETE for Invoice api
     * @param string $id
     * @return boolean
     */
    public function deleteInvoice($id);

    /**
     * Retrieve Invoice matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );
}
