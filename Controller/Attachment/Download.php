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

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

class Download extends \Magento\Framework\App\Action\Action
{

    protected $resultRawFactory;
    protected $customerSession;
    protected $fileFactory;
    protected $attachmentRepository;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Dealer4Dealer\SubstituteOrders\Model\AttachmentRepository $attachmentRepository
    ) {
        $this->resultRawFactory = $resultRawFactory;
        $this->customerSession = $customerSession;
        $this->fileFactory = $fileFactory;
        $this->attachmentRepository = $attachmentRepository;
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
        $magentoCustomerIdentifier = $customer->getData('entity_id');

        if ($attachment->getMagentoCustomerIdentifier() != $magentoCustomerIdentifier) {
            $this->messageManager->addError(__('Permission to download attachment denied'));
            return $this->resultRedirectFactory->create()->setPath('substitute/order');
        }

        try {
            $fileNameParts = explode('/', $attachment->getFile());
            $fileName = end($fileNameParts);
            $filePath = $attachment->getBasePath(
                $attachment->getMagentoCustomerIdentifier(),
                $attachment->getEntityType()
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
                ''
            );
        } catch (\Exception $exception) {
            $this->messageManager->addError($exception->getMessage());
            return $this->resultRedirectFactory->create()->setPath('substitute/order');
        }

        $resultRaw = $this->resultRawFactory->create();
        return $resultRaw;
    }
}
