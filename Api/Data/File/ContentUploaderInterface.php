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
interface ContentUploaderInterface
{
    /**
     * Upload provided downloadable file content
     *
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\File\ContentInterface $fileContent
     * @param string $customerIdentifier
     * @param string $entityType
     * @return array
     * @throws \InvalidArgumentException
     */
    public function upload(ContentInterface $fileContent, $customerIdentifier, $entityType);
}
