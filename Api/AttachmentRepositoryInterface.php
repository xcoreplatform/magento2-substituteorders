<?php


namespace Dealer4Dealer\SubstituteOrders\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface AttachmentRepositoryInterface
{


    /**
     * Save Attachment
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\AttachmentInterface $attachment
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\AttachmentInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Dealer4Dealer\SubstituteOrders\Api\Data\AttachmentInterface $attachment
    );

    /**
     * Retrieve Attachment
     * @param string $attachmentId
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\AttachmentInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($attachmentId);

    /**
     * Retrieve Attachment matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\AttachmentSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Attachment
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\AttachmentInterface $attachment
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Dealer4Dealer\SubstituteOrders\Api\Data\AttachmentInterface $attachment
    );

    /**
     * Delete Attachment by ID
     * @param string $attachmentId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($attachmentId);

    /**
     * Retrieve Shipments for given order.
     * @param string $entityTypeIdentifier
     * @param string $entityType
     * @return Data\AttachmentSearchResultsInterface
     */
    public function getAttachmentsByEntityTypeIdentifier(
        $entityTypeIdentifier,
        $magentoCustomerIdentifier,
        $entityType = 'order'
    );
}
