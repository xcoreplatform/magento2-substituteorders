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

namespace Dealer4Dealer\SubstituteOrders\Block\Order;

class Attachment extends \Magento\Framework\View\Element\Template
{

    protected $_entity;

    protected $_attachmentCollection;

    public function setEntity($entity)
    {
        $this->_entity = $entity;
        $this->_attachmentCollection = null;
        // Changing model and resource model can lead to change of comment collection
        return $this;
    }

    public function getEntity()
    {
        return $this->_entity;
    }

    public function getAttachments()
    {
        if ($this->_attachmentCollection === null) {
            $collection = $this->getEntity()->getAttachmentCollection();

            if (empty($collection)) {
                return [];
            }

            $items = [];
            foreach ($collection as $id => $item) {
                $fileNameParts = explode('/', $item->getFile());
                $item['file_name'] = end($fileNameParts);
                $item['url'] = $this->getUrl('substitute/attachment/download', ['id' => $item->getId()]);
                $items[$id] = $item;
            }
            $this->_attachmentCollection = $items;
        }

        return $this->_attachmentCollection;
    }

    public function hasAttachments()
    {
        return (!empty($this->getAttachments()));
    }
}
