<?php


namespace Dealer4Dealer\SubstituteOrders\Model;

use Dealer4Dealer\SubstituteOrders\Api\Data\AttachmentInterface;

class Attachment extends \Magento\Framework\Model\AbstractModel implements AttachmentInterface
{

    /**
     * @var string
     */
    const ENTITY = 'attachment';

    /**
     * @var string
     */
    protected $_eventPrefix = 'substitute_order_attachment';

    /**
     * @var string
     */
    protected $_eventObject = 'attachment';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Dealer4Dealer\SubstituteOrders\Model\ResourceModel\Attachment');
    }

    /**
     * Get attachment_id
     * @return string
     */
    public function getAttachmentId()
    {
        return $this->getData(self::ATTACHEMENT_ID);
    }

    /**
     * Set attachment_id
     * @param string $attachmentId
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\AttachmentInterface
     */
    public function setAttachmentId($attachmentId)
    {
        return $this->setData(self::ATTACHEMENT_ID, $attachmentId);
    }

    /**
     * Get magento_customer_identifier
     * @return string
     */
    public function getMagentoCustomerIdentifier()
    {
        return $this->getData(self::MAGENTO_CUSTOMER_IDENTIFIER);
    }

    /**
     * Set magento_customer_identifier
     * @param string $magento_customer_identifier
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\AttachmentInterface
     */
    public function setMagentoCustomerIdentifier($magento_customer_identifier)
    {
        return $this->setData(self::MAGENTO_CUSTOMER_IDENTIFIER, $magento_customer_identifier);
    }

    /**
     * Get file
     * @return string
     */
    public function getFile()
    {
        return $this->getData(self::FILE);
    }

    /**
     * Set file
     * @param string $file
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\AttachmentInterface
     */
    public function setFile($file)
    {
        return $this->setData(self::FILE, $file);
    }

    /**
     * Get type
     * @return string
     */
    public function getEntityType()
    {
        return $this->getData(self::ENTITY_TYPE);
    }

    /**
     * Set entity_type
     * @param string $entity_type
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\AttachmentInterface
     */
    public function setEntityType($entity_type)
    {
        return $this->setData(self::ENTITY_TYPE, $entity_type);
    }

    /**
     * Get type
     * @return string
     */
    public function getEntityTypeIdentifier()
    {
        return $this->getData(self::ENTITY_TYPE_IDENTIFIER);
    }

    /**
     * Set entity_type_identifier
     * @param string $entity_type_identifier
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\AttachmentInterface
     */
    public function setEntityTypeIdentifier($entity_type_identifier)
    {
        return $this->setData(self::ENTITY_TYPE_IDENTIFIER, $entity_type_identifier);
    }

    /**
     * Set file content
     *
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\File\ContentInterface $fileContent
     * @return $this
     */
    public function setFileContent(\Dealer4Dealer\SubstituteOrders\Api\Data\File\ContentInterface $fileContent = null)
    {
        return $this->setData(self::FILE_CONTENT, $fileContent);
    }

    /**
     * Return file content
     *
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\File\ContentInterface|null
     */
    public function getFileContent()
    {
        return $this->getData(self::FILE_CONTENT);
    }

    /**
     * Retrieve Base files path
     *
     * @return string
     */
    public function getBasePath($entityType,$customerIdentifier = '0')
    {
        return 'customer/substitute_order/files/' . $customerIdentifier . '/' . $entityType;
    }
}
