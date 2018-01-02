<?php


namespace Dealer4Dealer\SubstituteOrders\Api\Data;

interface AttachmentInterface
{

    const ENTITY_TYPE = 'entity_type';
    const ENTITY_TYPE_IDENTIFIER = 'entity_type_identifier';
    const FILE = 'file';
    const MAGENTO_CUSTOMER_IDENTIFIER = 'magento_customer_identifier';
    const ATTACHEMENT_ID = 'attachment_id';
    const FILE_CONTENT = 'file_content';


    /**
     * Get attachment_id
     * @return string|null
     */
    public function getAttachmentId();

    /**
     * Set attachment_id
     * @param string $attachment_id
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\AttachmentInterface
     */
    public function setAttachmentId($attachmentId);

    /**
     * Get magento_customer_identifier
     * @return string|null
     */
    public function getMagentoCustomerIdentifier();

    /**
     * Set magento_customer_identifier
     * @param string $magento_customer_identifier
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\AttachmentInterface
     */
    public function setMagentoCustomerIdentifier($magento_customer_identifier);

    /**
     * Get file
     * @return string|null
     */
    public function getFile();

    /**
     * Set file
     * @param string $file
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\AttachmentInterface
     */
    public function setFile($file);

    /**
     * Get type
     * @return string|null
     */
    public function getEntityType();

    /**
     * Set type
     * @param string $entity_type
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\AttachmentInterface
     */
    public function setEntityType($entity_type);

    /**
     * Get type
     * @return string|null
     */
    public function getEntityTypeIdentifier();

    /**
     * Set type
     * @param string $entity_type_identifier
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\AttachmentInterface
     */
    public function setEntityTypeIdentifier($entity_type_identifier);

    /**
     * Return file content
     *
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\File\ContentInterface|null
     */
    public function getFileContent();

    /**
     * Set file content
     *
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\File\ContentInterface $fileContent
     * @return $this
     */
    public function setFileContent(\Dealer4Dealer\SubstituteOrders\Api\Data\File\ContentInterface $fileContent = null);
}
