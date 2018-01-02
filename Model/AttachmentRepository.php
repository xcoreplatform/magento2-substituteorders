<?php


namespace Dealer4Dealer\SubstituteOrders\Model;

use Dealer4Dealer\SubstituteOrders\Api\Data\AttachmentSearchResultsInterfaceFactory;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotSaveException;
use Dealer4Dealer\SubstituteOrders\Api\AttachmentRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Dealer4Dealer\SubstituteOrders\Model\ResourceModel\Attachment as ResourceAttachment;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Exception\CouldNotDeleteException;
use Dealer4Dealer\SubstituteOrders\Model\ResourceModel\Attachment\CollectionFactory as AttachmentCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Dealer4Dealer\SubstituteOrders\Api\Data\AttachmentInterfaceFactory;
use Dealer4Dealer\SubstituteOrders\Api\Data\File\ContentUploaderInterface;

class AttachmentRepository implements attachmentRepositoryInterface
{

    protected $attachmentCollectionFactory;

    private $storeManager;

    protected $dataObjectProcessor;

    protected $dataObjectHelper;

    protected $dataAttachmentFactory;

    protected $searchResultsFactory;

    protected $resource;

    protected $attachmentFactory;

    protected $fileContentUploader;

    protected $contentValidator;

    /**
     * @param ResourceAttachment $resource
     * @param AttachmentFactory $attachmentFactory
     * @param AttachmentInterfaceFactory $dataAttachmentFactory
     * @param AttachmentCollectionFactory $attachmentCollectionFactory
     * @param AttachmentSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param ContentUploaderInterface $fileContentUploader
     * @param File\ContentValidator $contentValidator
     */
    public function __construct(
        ResourceAttachment $resource,
        AttachmentFactory $attachmentFactory,
        AttachmentInterfaceFactory $dataAttachmentFactory,
        AttachmentCollectionFactory $attachmentCollectionFactory,
        AttachmentSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        ContentUploaderInterface $fileContentUploader,
        File\ContentValidator $contentValidator
    ) {
        $this->resource = $resource;
        $this->attachmentFactory = $attachmentFactory;
        $this->attachmentCollectionFactory = $attachmentCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataAttachmentFactory = $dataAttachmentFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->fileContentUploader = $fileContentUploader;
        $this->contentValidator = $contentValidator;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Dealer4Dealer\SubstituteOrders\Api\Data\AttachmentInterface $attachment
    ) {
        /* if (empty($attachment->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $attachment->setStoreId($storeId);
        } */

        $this->contentValidator->isValid($attachment->getFileContent());

        $result = $this->fileContentUploader->upload($attachment->getFileContent(), $attachment->getMagentoCustomerIdentifier(), $attachment->getEntityType());
        $attachment->setFile($result['file']);

        try {
            $attachment->getResource()->save($attachment);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the attachment: %1',
                $exception->getMessage()
            ));
        }
        return $attachment;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($attachmentId)
    {
        $attachment = $this->attachmentFactory->create();
        $attachment->getResource()->load($attachment, $attachmentId);
        if (!$attachment->getId()) {
            throw new NoSuchEntityException(__('Attachment with id "%1" does not exist.', $attachmentId));
        }
        return $attachment;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->attachmentCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Dealer4Dealer\SubstituteOrders\Api\Data\AttachmentInterface $attachment
    ) {

        $attachmentFilePath = $this->fileContentUploader->getDestinationDirectory(
            $attachment->getMagentoCustomerIdentifier(),
            $attachment->getEntityType()
        ) . $attachment->getFile();

        if (file_exists($attachmentFilePath)) {
            unlink($attachmentFilePath);
        }

        try {
            $attachment->getResource()->delete($attachment);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Attachment: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($attachmentId)
    {
        return $this->delete($this->getById($attachmentId));
    }


    public function saveAttachmentByEntityType(
        $entityType,
        $entityTypeIdentifier,
        $magentoCustomerIdentifier,
        array $fileContent
    ) {

        $this->deleteAttachmentsByEntityTypeIdentifier($entityTypeIdentifier, $magentoCustomerIdentifier, $entityType);

        foreach ($fileContent as $file) {
            /* @var $attachment \Dealer4Dealer\SubstituteOrders\Model\Attachment */
            $attachment = $this->attachmentFactory->create();
            $attachment->setFileContent($file);
            $attachment->setMagentoCustomerIdentifier($magentoCustomerIdentifier);
            $attachment->setEntityType($entityType);
            $attachment->setEntityTypeIdentifier($entityTypeIdentifier);
            $this->save($attachment);
        }
    }

    public function deleteAttachmentsByEntityTypeIdentifier($entityTypeIdentifier, $magentoCustomerIdentifier, $entityType = 'order')
    {
        $attachments = $this->getAttachmentsByEntityTypeIdentifier($entityTypeIdentifier, $magentoCustomerIdentifier, $entityType);

        foreach ($attachments as $attachment) {
            /* @var $attachment \Dealer4Dealer\SubstituteOrders\Model\Attachment */
            $this->delete($attachment);
        }
    }

    public function getAttachmentsByEntityTypeIdentifier($entityTypeIdentifier, $magentoCustomerIdentifier, $entityType = 'order')
    {
        /* @var $collection \Dealer4Dealer\SubstituteOrders\Model\ResourceModel\Attachment\Collection */
        $collection = $this->attachmentCollectionFactory->create();

        $collection->addFieldToFilter('entity_type_identifier', $entityTypeIdentifier);
        $collection->addFieldToFilter('entity_type', $entityType);
        $collection->addFieldToFilter('magento_customer_identifier', $magentoCustomerIdentifier);

        return $collection->getItems();
    }
}
