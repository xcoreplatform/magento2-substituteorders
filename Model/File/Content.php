<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Dealer4Dealer\SubstituteOrders\Model\File;

use Dealer4Dealer\SubstituteOrders\Api\Data\File\ContentInterface;

/**
 * @codeCoverageIgnore
 */
class Content extends \Magento\Framework\Model\AbstractExtensibleModel implements ContentInterface
{
    const DATA = 'file_data';
    const NAME = 'name';

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function getFileData()
    {
        return $this->getData(self::DATA);
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * Set data (base64 encoded content)
     *
     * @param string $fileData
     * @return $this
     * @codeCoverageIgnore
     */
    public function setFileData($fileData)
    {
        return $this->setData(self::DATA, $fileData);
    }

    /**
     * Set file name
     *
     * @param string $name
     * @return $this
     * @codeCoverageIgnore
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }
}
