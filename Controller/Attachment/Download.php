<?php
/**
 * A Magento 2 module named Dealer4Dealer\SubstituteOrders
 * Copyright (C) 2017 Maikel Martens
 *
 * This file is part of Dealer4Dealer\SubstituteOrders.
 *
 * Dealer4Dealer\SubstituteOrders is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Dealer4Dealer\SubstituteOrders\Controller\Attachment;

use Dealer4Dealer\SubstituteOrders\Api\Data\InvoiceInterface;
use Dealer4Dealer\SubstituteOrders\Model\Attachment;
use Magento\Customer\Model\Customer;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

class Download extends \Magento\Framework\App\Action\Action
{

    protected $resultRawFactory;
    protected $customerSession;
    protected $fileFactory;
    protected $attachmentRepository;

    /** @var \Magento\Customer\Api\CustomerRepositoryInterface  */
    protected $_customerRepository;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface */
    protected $_scopeConfig;

    /** @var \Dealer4Dealer\SubstituteOrders\Model\OrderFactory */
    protected $orderFactory;

    /** @var \Dealer4Dealer\SubstituteOrders\Model\InvoiceFactory */
    protected $invoiceFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Dealer4Dealer\SubstituteOrders\Model\AttachmentRepository $attachmentRepository,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Dealer4Dealer\SubstituteOrders\Model\OrderFactory $orderFactory,
        \Dealer4Dealer\SubstituteOrders\Model\InvoiceFactory $invoiceFactory
    ) {
        $this->resultRawFactory = $resultRawFactory;
        $this->customerSession = $customerSession;
        $this->fileFactory = $fileFactory;
        $this->attachmentRepository = $attachmentRepository;
        $this->orderFactory = $orderFactory;
        $this->invoiceFactory = $invoiceFactory;

        $this->_scopeConfig = $scopeConfig;
        $this->_customerRepository = $customerRepository;

        parent::__construct($context);
    }

    /**
     * Check customer authentication
     *
     * @param RequestInterface $request
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function dispatch(RequestInterface $request)
    {
        $loginUrl = $this->_objectManager->get('Magento\Customer\Model\Url')->getLoginUrl();

        if (!$this->customerSession->authenticate($loginUrl)) {
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
        }
        return parent::dispatch($request);
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        if (!$id) {
            $this->messageManager->addError(__('Cannot find attachment'));
            return $this->resultRedirectFactory->create()->setPath('substitute/order');
        }

        $attachment = $this->attachmentRepository->getById($id);
        $customer = $this->customerSession->getCustomer();

        if (!$this->canDownloadAttachment($attachment, $customer)) {
            $this->messageManager->addError(__('Permission to download attachment denied'));
            return $this->resultRedirectFactory->create()->setPath('substitute/order');
        }

        try {
            $fileNameParts = explode('/', $attachment->getFile());
            $fileName = end($fileNameParts);
            $filePath = $attachment->getBasePath(
                $attachment->getEntityType(),
                $attachment->getMagentoCustomerIdentifier()
            );
            $filePath .= $attachment->getFile();

            $this->fileFactory->create(
                $fileName,
                [
                    'type' => 'filename',
                    'value' => $filePath
                ],
                DirectoryList::MEDIA,
                'application/octet-stream',
                '')->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        } catch (\Exception $exception) {
            $this->messageManager->addError($exception->getMessage());
            return $this->resultRedirectFactory->create()->setPath('substitute/order');
        }

        $resultRaw = $this->resultRawFactory->create();
        return $resultRaw;
    }

    /**
     * @param $attachment \Dealer4Dealer\SubstituteOrders\Api\Data\AttachmentInterface
     * @param $customer Customer
     */
    public function canDownloadAttachment($attachment, $customer){
         /** @var int $customerId */
        $customerId = $customer->getid();
        /** @var \Magento\Customer\Api\Data\CustomerInterface $mCustomer */
        $mCustomer = $this->_customerRepository->getById($customerId);

        /** @var \Magento\Framework\Api\AttributeInterface */
        $externalCustomerIdAttribute = $mCustomer->getCustomAttribute("external_customer_id");
        $selectOrderBySetting = $this->_scopeConfig->getValue(
            'substitute/general/select_orders_by',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        // als ik de order mag zien, dan ook de bijlage.
        $entityId = $attachment->getEntityTypeIdentifier();
        if ($attachment->getEntityType() === 'order') {
            $order = $this->orderFactory->create()->load($entityId);
        } else{
            /** @var InvoiceInterface $invoice */
            $invoice = $this->invoiceFactory->create()->load($entityId);
            $orders = $invoice->getOrderIds();
            $order = $this->orderFactory->create()->load($orders[0]);
        }

        $customerSelectionId = $customerId;
        if ($selectOrderBySetting === 'external_customer_id' && $externalCustomerIdAttribute !== null && $externalCustomerIdAttribute->getValue() !== ''){
            $customerSelectionId = $externalCustomerIdAttribute->getValue();

            $event = new \Magento\Framework\DataObject([
                'order' => $order,
                'customer' => $mCustomer,
                'hasAccess' => $order->getData($selectOrderBySetting) == $customerSelectionId,
            ]);
        } else {
            $event = new \Magento\Framework\DataObject([
                'order' => $order,
                'customer' => $mCustomer,
                'hasAccess' => $order->getData('magento_customer_id') == $customerSelectionId,
            ]);
        }

        $this->_eventManager->dispatch(
            'substituteorder_customer_has_order_access',
            ['event' => $event]
        );

        return $event->getData('hasAccess');
    }
}
