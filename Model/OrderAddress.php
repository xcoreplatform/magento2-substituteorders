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

use Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface;

class OrderAddress extends \Magento\Framework\Model\AbstractModel implements OrderAddressInterface
{

    /**
     * @var string
     */
    const ENTITY = 'order_address';

    /**
     * @var string
     */
    protected $_eventPrefix = 'substitute_order_order_adress';

    /**
     * @var string
     */
    protected $_eventObject = 'address';

    protected $_additionalData = null;

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Dealer4Dealer\SubstituteOrders\Model\ResourceModel\OrderAddress');
    }

    public function save()
    {
        if ($this->_additionalData) {
            $data = [];
            foreach ($this->_additionalData as $value) {
                $data[$value->getKey()] = $value->getValue();
            }

            $this->setData(self::ADDITIONAL_DATA, json_encode($data));
        }

        return parent::save();
    }

    /**
     * Get full customer name
     *
     * @return string
     */
    public function getName()
    {
        $name = '';
        if ($this->getPrefix()) {
            $name .= $this->getPrefix() . ' ';
        }
        $name .= $this->getFirstname();
        if ($this->getMiddlename()) {
            $name .= ' ' . $this->getMiddlename();
        }
        $name .= ' ' . $this->getLastname();
        if ($this->getSuffix()) {
            $name .= ' ' . $this->getSuffix();
        }
        return $name;
    }


    /**
     * Get orderaddress_id
     * @return string
     */
    public function getOrderaddressId()
    {
        return $this->getData(self::ORDERADDRESS_ID);
    }

    /**
     * Set orderaddress_id
     * @param string $orderaddressId
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setOrderaddressId($orderaddressId)
    {
        return $this->setData(self::ORDERADDRESS_ID, $orderaddressId);
    }

    /**
     * Get name
     * @return string
     */
    public function getFirstname()
    {
        return $this->getData(self::FIRSTNAME);
    }

    /**
     * Set name
     * @param string $name
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setFirstname($name)
    {
        return $this->setData(self::FIRSTNAME, $name);
    }

    /**
     * Get name
     * @return string
     */
    public function getMiddlename()
    {
        return $this->getData(self::MIDDLENAME);
    }

    /**
     * Set name
     * @param string $name
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setMiddlename($name)
    {
        return $this->setData(self::MIDDLENAME, $name);
    }

    /**
     * Get name
     * @return string
     */
    public function getLastname()
    {
        return $this->getData(self::LASTNAME);
    }

    /**
     * Set name
     * @param string $name
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setLastname($name)
    {
        return $this->setData(self::LASTNAME, $name);
    }

    /**
     * Get name
     * @return string
     */
    public function getSuffix()
    {
        return $this->getData(self::SUFFIX);
    }

    /**
     * Set name
     * @param string $name
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setSuffix($name)
    {
        return $this->setData(self::SUFFIX, $name);
    }

    /**
     * Get name
     * @return string
     */
    public function getPrefix()
    {
        return $this->getData(self::PREFIX);
    }

    /**
     * Set name
     * @param string $name
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setPrefix($name)
    {
        return $this->setData(self::PREFIX, $name);
    }

    /**
     * Get company
     * @return string
     */
    public function getCompany()
    {
        return $this->getData(self::COMPANY);
    }

    /**
     * Set company
     * @param string $company
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setCompany($company)
    {
        return $this->setData(self::COMPANY, $company);
    }

    /**
     * Get street
     * @return string
     */
    public function getStreet()
    {
        return $this->getData(self::STREET);
    }

    /**
     * Set street
     * @param string $street
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setStreet($street)
    {
        return $this->setData(self::STREET, $street);
    }

    /**
     * Get postcode
     * @return string
     */
    public function getPostcode()
    {
        return $this->getData(self::POSTCODE);
    }

    /**
     * Set postcode
     * @param string $postcode
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setPostcode($postcode)
    {
        return $this->setData(self::POSTCODE, $postcode);
    }

    /**
     * Get city
     * @return string
     */
    public function getCity()
    {
        return $this->getData(self::CITY);
    }

    /**
     * Set city
     * @param string $city
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setCity($city)
    {
        return $this->setData(self::CITY, $city);
    }

    /**
     * Get country
     * @return string
     */
    public function getCountry()
    {
        return $this->getData(self::COUNTRY);
    }

    /**
     * Set country
     * @param string $country
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setCountry($country)
    {
        return $this->setData(self::COUNTRY, $country);
    }

    /**
     * Get phone
     * @return string
     */
    public function getTelephone()
    {
        return $this->getData(self::PHONE);
    }

    /**
     * Set phone
     * @param string $phone
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setTelephone($phone)
    {
        return $this->setData(self::PHONE, $phone);
    }

    /**
     * Get fax
     * @return string
     */
    public function getFax()
    {
        return $this->getData(self::FAX);
    }

    /**
     * Set fax
     * @param string $fax
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderAddressInterface
     */
    public function setFax($fax)
    {
        return $this->setData(self::FAX, $fax);
    }

    /**
     * @inheritDoc
     */
    public function getAdditionalData()
    {
        if ($this->_additionalData == null) {
            $this->_additionalData = [];

            if ($this->getData(self::ADDITIONAL_DATA)) {
                $data = json_decode($this->getData(self::ADDITIONAL_DATA), true);
                foreach ($data as $key => $value) {
                    $this->_additionalData[] = new AdditionalData($key, $value);
                }
            }
        }
        return $this->_additionalData;
    }

    /**
     * @inheritDoc
     */
    public function setAdditionalData($additional_data)
    {
        $this->_additionalData = $additional_data;
        return $this;
    }

    public function setData($key, $value = null)
    {
        parent::setData($key, $value);
        return $this;
    }
}
