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

interface OrderAddressInterface
{
    const ORDERADDRESS_ID   = 'orderaddress_id';
    const ORDER_ID          = 'order_id';
    const PREFIX            = 'prefix';
    const FIRSTNAME         = 'firstname';
    const MIDDLENAME        = 'middlename';
    const LASTNAME          = 'lastname';
    const SUFFIX            = 'suffix';
    const COMPANY           = 'company';
    const STREET            = 'street';
    const POSTCODE          = 'postcode';
    const CITY              = 'city';
    const COUNTRY           = 'country';
    const PHONE             = 'telephone';
    const FAX               = 'fax';
    const ADDITIONAL_DATA   = 'additional_data';

    /**
     * Get orderaddress_id
     * @return string|null
     */
    public function getOrderaddressId();

    /**
     * Set orderaddress_id
     * @param string $orderaddress_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setOrderaddressId($orderaddressId);


    /**
     * Get name
     * @return string|null
     */
    public function getFirstname();

    /**
     * Set name
     * @param string $name
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setFirstname($name);
    /**
     * Get name
     * @return string|null
     */
    public function getMiddlename();

    /**
     * Set name
     * @param string $name
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setMiddlename($name);
    
    /**
     * Get name
     * @return string|null
     */
    public function getLastname();

    /**
     * Set name
     * @param string $name
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setLastname($name);

    /**
     * Get name
     * @return string|null
     */
    public function getPrefix();

    /**
     * Set name
     * @param string $name
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setPrefix($name);
    
    /**
     * Get name
     * @return string|null
     */
    public function getSuffix();

    /**
     * Set name
     * @param string $name
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setSuffix($name);
    
    
    
    /**
     * Get company
     * @return string|null
     */
    public function getCompany();

    /**
     * Set company
     * @param string $company
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setCompany($company);

    /**
     * Get street
     * @return string|null
     */
    public function getStreet();

    /**
     * Set street
     * @param string $street
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setStreet($street);

    /**
     * Get postcode
     * @return string|null
     */
    public function getPostcode();

    /**
     * Set postcode
     * @param string $postcode
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setPostcode($postcode);

    /**
     * Get city
     * @return string|null
     */
    public function getCity();

    /**
     * Set city
     * @param string $city
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setCity($city);

    /**
     * Get country
     * @return string|null
     */
    public function getCountry();

    /**
     * Set country
     * @param string $country
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setCountry($country);

    /**
     * Get phone
     * @return string|null
     */
    public function getTelephone();

    /**
     * Set phone
     * @param string $phone
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setTelephone($phone);

    /**
     * Get fax
     * @return string|null
     */
    public function getFax();

    /**
     * Set fax
     * @param string $fax
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setFax($fax);

    /**
     * Get additional_data
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\AdditionalDataInterface[]
     */
    public function getAdditionalData();

    /**
     * Set additional_data
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\AdditionalDataInterface[] $additional_data
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setAdditionalData($additional_data);
}
