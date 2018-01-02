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

use Dealer4Dealer\SubstituteOrders\Api\Data\Dealer4Dealer;
use Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentTrackingInterface;

class ShipmentTracking implements ShipmentTrackingInterface
{

    protected $carrierName = '';
    protected $code = '';
    protected $url = '';

    /**
     * ShipmentTracking constructor.
     * @param $carrierName
     * @param $code
     * @param string $url
     */
    public function __construct($carrierName = '', $code = '', $url = '')
    {
        $this->carrierName = $carrierName;
        $this->code = $code;
        $this->url = $url;
    }

    public static function createByArray($data)
    {
        return new ShipmentTracking(@$data['name'], @$data['code'], @$data['url']);
    }

    public function getArray()
    {
        return [
            'name' => $this->carrierName,
            'code' => $this->code,
            'url' => $this->url,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getCarrierName()
    {
        return $this->carrierName;
    }

    /**
     * @inheritDoc
     */
    public function setCarrierName($carrier_name)
    {
        $this->carrierName = $carrier_name;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @inheritDoc
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @inheritDoc
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }
}
