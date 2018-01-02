<?php


namespace Dealer4Dealer\SubstituteOrders\Model\ResourceModel;

class Attachment extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('dealer4dealer_substituteorders_attachment', 'attachment_id');
    }
}
