<?php
/**
 * Created by PhpStorm.
 * User: Daniel Oldersma
 * Date: 18/03/2019
 * Time: 19:47
 */

namespace Dealer4Dealer\SubstituteOrders\Model\Config;

class Version extends \Magento\Framework\App\Config\Value
{
    /** @var \Magento\Framework\App\Config\Value */
    protected $moduleResource;

    /**
     * Version constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\Module\ResourceInterface $moduleResource
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\Module\ResourceInterface $moduleResource,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [])
    {
        $this->moduleResource = $moduleResource;

        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $resource,
            $resourceCollection,
            $data);
    }

    public function afterLoad()
    {
        $version = $this->moduleResource->getDbVersion("Dealer4Dealer_SubstituteOrders");
        $this->setValue($version);

        //return parent::afterLoad();
    }
}