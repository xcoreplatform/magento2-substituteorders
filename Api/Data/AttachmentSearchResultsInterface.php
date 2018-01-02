<?php


namespace Dealer4Dealer\SubstituteOrders\Api\Data;

interface AttachmentSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get Attachment list.
     * @return \Dealer4Dealer\SubstituteOrders\Api\Data\AttachmentInterface[]
     */
    public function getItems();

    /**
     * Set magento_customer_identifier list.
     * @param \Dealer4Dealer\SubstituteOrders\Api\Data\AttachmentInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
