<?php
namespace Dealer4Dealer\SubstituteOrders\Model\Config\Source;

class SelectOrdersBy implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [['value' => 'magento_customer_id', 'label' => __('Magento Customer ID')], ['value' => 'external_customer_id', 'label' => __('External Customer ID')]];
    }
    
    public function toArray()
    {
        return ['magento_customer_id' => __('Magento Customer ID'), 'external_customer_id' => __('External Customer ID')];
    }
}