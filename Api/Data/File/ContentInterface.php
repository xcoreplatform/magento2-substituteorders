<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Dealer4Dealer\SubstituteOrders\Api\Data\File;

/**
 * @codeCoverageIgnore
 * @api
 * @since 100.0.2
 */
interface ContentInterface
{
    /**
     * Retrieve data (base64 encoded content)
     *
     * @return string
     */
    public function getFileData();

    /**
     * Set data (base64 encoded content)
     *
     * @param string $fileData
     * @return $this
     */
    public function setFileData($fileData);

    /**
     * Retrieve file name
     *
     * @return string
     */
    public function getName();

    /**
     * Set file name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);
}
