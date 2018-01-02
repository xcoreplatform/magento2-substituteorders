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

namespace Dealer4Dealer\SubstituteOrders\Api\Data;

interface ShipmentTrackingInterface
{

    /**
     * Get Carrier name
     * @return string|null
     */
    public function getCarrierName();

    /**
     * Set Carrier name
     * @param string $carrier_name
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentTrackingInterface
     */
    public function setCarrierName($carrier_name);

    /**
     * Get code
     * @return string|null
     */
    public function getCode();

    /**
     * Set code
     * @param string $code
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentTrackingInterface
     */
    public function setCode($code);

    /**
     * Get url
     * @return string|null
     */
    public function getUrl();

    /**
     * Set url
     * @param string $url
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentTrackingInterface
     */
    public function setUrl($url);
}
