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

namespace Dealer4Dealer\SubstituteOrders\Block\Order\History;

/**
 * Sales order history extra container block
 */
class Container extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    protected $order;

    /**
     * Set order
     *
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @return $this
     */
    public function setOrder(\Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface $order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * Get order
     *
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\OrderInterface
     */
    private function getOrder()
    {
        return $this->order;
    }

    /**
     * Here we set an order for children during retrieving their HTML
     *
     * @param string $alias
     * @param bool $useCache
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getChildHtml($alias = '', $useCache = false)
    {
        $layout = $this->getLayout();
        if ($layout) {
            $name = $this->getNameInLayout();
            foreach ($layout->getChildBlocks($name) as $child) {
                $child->setOrder($this->getOrder());
            }
        }
        return parent::getChildHtml($alias, $useCache);
    }
}
