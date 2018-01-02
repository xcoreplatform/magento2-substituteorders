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
     * @param ContentInterface $fileContent
     * @param string $contentType
     * @return array
     * @throws \InvalidArgumentException
     */
    public function upload(ContentInterface $fileContent, $customerIdentifier, $entityType);
}
