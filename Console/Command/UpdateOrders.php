<?php

namespace Dealer4Dealer\SubstituteOrders\Console\Command;

use Dealer4Dealer\SubstituteOrders\Observer\Sales\OrderInvoiceSaveAfter;
use Dealer4Dealer\SubstituteOrders\Observer\Sales\OrderShipmentSaveAfter;
use Magento\Sitemap\Model\Observer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateOrders extends Command
{
    /**@var \Dealer4Dealer\SubstituteOrders\Observer\Sales\OrderSaveAfter */
    protected $orderSaveAfter;

    /** @var \Dealer4Dealer\SubstituteOrders\Observer\Sales\OrderInvoiceSaveAfter */
    protected $invoiceSaveAfter;

    /** @var \Dealer4Dealer\SubstituteOrders\Observer\Sales\OrderShipmentSaveAfter */
    protected $shipmentSaveAfter;

    /**@var \Magento\Framework\ObjectManagerInterface */
    protected $objectManager;

    /**@var \Magento\Sales\Model\OrderFactory */
    protected $orderFactory;

    /** @var \Magento\Framework\App\State */
    protected $state;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Dealer4Dealer\SubstituteOrders\Observer\Sales\OrderSaveAfter $orderSaveAfter,
        \Dealer4Dealer\SubstituteOrders\Observer\Sales\OrderInvoiceSaveAfter $orderInvoiceSaveAfter,
        \Dealer4Dealer\SubstituteOrders\Observer\Sales\OrderShipmentSaveAfter $shipmentSaveAfter,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Framework\App\State $state,
        $name = null
    ) {
        parent::__construct($name);

        $this->objectManager = $objectManager;
        $this->orderSaveAfter = $orderSaveAfter;
        $this->invoiceSaveAfter = $orderInvoiceSaveAfter;
        $this->shipmentSaveAfter = $shipmentSaveAfter;
        $this->orderFactory = $orderFactory;
        $this->state = $state;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_FRONTEND);

        $collection = $this->orderFactory->create()->getCollection();
        $size = $collection->getSize();
        $maxPages = ceil($size / 250);
        $page = 1;

        while (($page - 1) * 250 < $size) {
            print("Processing page {$page} of {$maxPages}\n");

            $collection->clear()->setPageSize(250)->setCurPage($page)->load();
            /** @var \Magento\Sales\Model\Order $order */
            foreach ($collection as $order) {
                // Copy the order

                /** @var \Magento\Framework\Event\Observer $observer */
                $observer = $this->objectManager->get('\Magento\Framework\Event\Observer');
                $observer->setOrder($order);

                try {
                    $this->orderSaveAfter->execute($observer);
                } catch (\Exception $e) {
                    print("ERROR: Could not update order {$order->getIncrementId()}: {$e->getMessage()}\n");
                }

                // Copy the invoices
                foreach ($order->getInvoiceCollection() as $invoice){
                    /** @var \Magento\Framework\Event\Observer */
                    $observer1 = $this->objectManager->get('\Magento\Framework\Event\Observer');
                    $observer1->setInvoice($invoice);

                    try{
                        $this->invoiceSaveAfter->execute($observer1);
                    } catch (\Exception $e){
                        print("ERROR: Could not update invoice: {$e->getMessage()}\n");
                    }
                }

                foreach($order->getShipmentsCollection() as $shipment){
                    /** @var \Magento\Framework\Event\Observer */
                    $observer = $this->objectManager->get('\Magento\Framework\Event\Observer');
                    $observer->setShipment($shipment);

                    try{
                        $this->shipmentSaveAfter->execute($observer);
                    } catch (\Exception $e){
                        print ("ERROR: Could not update shipment: {$e->getMessage()}\n");
                    }
                }

            }

            $page++;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("substituteorders:updateorders");
        $this->setDescription("Copy existing order information to substitute orders");
        parent::configure();
    }
}
