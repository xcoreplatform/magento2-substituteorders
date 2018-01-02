<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Dealer4Dealer\SubstituteOrders\Model\File;

use Magento\MediaStorage\Helper\File\Storage;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\MediaStorage\Model\File\Uploader;
use Magento\MediaStorage\Model\File\Validator\NotProtectedExtension;
use Dealer4Dealer\SubstituteOrders\Api\Data\File\ContentInterface;
use Dealer4Dealer\SubstituteOrders\Model\Attachment as AttachmentConfig;
use Magento\Downloadable\Model\Sample as SampleConfig;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;

class ContentUploader extends Uploader implements \Dealer4Dealer\SubstituteOrders\Api\Data\File\ContentUploaderInterface
{
    /**
     * Default MIME type
     */
    const DEFAULT_MIME_TYPE = 'application/octet-stream';

    /**
     * Filename prefix for temporary files
     *
     * @var string
     */
    protected $filePrefix = 'magento_api';

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $mediaDirectory;

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $systemTmpDirectory;

    /**
     * @var AttachmentConfig
     */
    protected $attachmentConfig;

    /**
     * @var SampleConfig
     */
    protected $sampleConfig;

    /**
     * @param Database $coreFileStorageDb
     * @param Storage $coreFileStorage
     * @param NotProtectedExtension $validator
     * @param Filesystem $filesystem
     * @param LinkConfig $linkConfig
     * @param SampleConfig $sampleConfig
     */
    public function __construct(
        Database $coreFileStorageDb,
        Storage $coreFileStorage,
        NotProtectedExtension $validator,
        Filesystem $filesystem,
        AttachmentConfig $attachmentConfig,
        SampleConfig $sampleConfig
    ) {
        $this->_validator = $validator;
        $this->_coreFileStorage = $coreFileStorage;
        $this->_coreFileStorageDb = $coreFileStorageDb;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->systemTmpDirectory = $filesystem->getDirectoryWrite(DirectoryList::SYS_TMP);
        $this->attachmentConfig = $attachmentConfig;
        $this->sampleConfig = $sampleConfig;
    }

    /**
     * Decode base64 encoded content and save it in system tmp folder
     *
     * @param ContentInterface $fileContent
     * @return array
     */
    protected function decodeContent(ContentInterface $fileContent)
    {
        $tmpFileName = $this->getTmpFileName();
        $fileSize = $this->systemTmpDirectory->writeFile($tmpFileName, base64_decode($fileContent->getFileData()));

        return [
            'name' => $fileContent->getName(),
            'type' => self::DEFAULT_MIME_TYPE,
            'tmp_name' => $this->systemTmpDirectory->getAbsolutePath($tmpFileName),
            'error' => 0,
            'size' => $fileSize,
        ];
    }

    /**
     * Generate temporary file name
     *
     * @return string
     */
    protected function getTmpFileName()
    {
        return uniqid("", false) . '.' . $this->getFileExtension();
    }

    /**
     * {@inheritdoc}
     */
    public function upload(ContentInterface $fileContent, $customerIdentifier, $entityType = 'order')
    {
        $this->_file = $this->decodeContent($fileContent);
        if (!file_exists($this->_file['tmp_name'])) {
            throw new \InvalidArgumentException('There was an error during file content upload.');
        }
        $this->_fileExists = true;
        $this->_uploadType = self::SINGLE_STYLE;
        $this->setAllowedExtensions(['pdf']);
        $this->setAllowRenameFiles(true);
        $this->setFilesDispersion(true);
        $result = $this->save($this->getDestinationDirectory($customerIdentifier, $entityType));
        unset($result['path']);
        $result['status'] = 'new';
        $result['name'] = substr($result['file'], strrpos($result['file'], '/') + 1);
        return $result;
    }

    /**
     * Retrieve destination directory for given content type
     *
     * @param string $contentType
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getDestinationDirectory($customerIdentifier, $entityType)
    {
        $directory = $this->mediaDirectory->getAbsolutePath($this->attachmentConfig->getBasePath($customerIdentifier, $entityType));
        return $directory;
    }
}
