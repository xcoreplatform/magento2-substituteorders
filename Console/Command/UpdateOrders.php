<?php

namespace Dealer4Dealer\SubstituteOrders\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateOrders extends Command
{

    /**@var \Dealer4Dealer\SubstituteOrders\Observer\Sales\OrderSaveAfter */
    protected $orderSaveAfter;

    /**@var \Magento\Framework\ObjectManagerInterface */
    protected $objectManager;

    /**@var \Magento\Sales\Model\OrderFactory */
    protected $orderFactory;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Dealer4Dealer\SubstituteOrders\Observer\Sales\OrderSaveAfter $orderSaveAfter,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        $name = null
    ) {
        parent::__construct($name);
        $this->objectManager = $objectManager;
        $this->orderSaveAfter = $orderSaveAfter;
        $this->orderFactory = $orderFactory;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $collection = $this->orderFactory->create()->getCollection();
        $size = $collection->getSize();
        $maxPages = ceil($size / 250);
        $page = 1;

        while (($page - 1) * 250 < $size) {
            print("Processing page {$page} of {$maxPages}\n");

            $collection->clear()->setPageSize(250)->setCurPage($page)->load();
            foreach ($collection as $order) {
                /** @var \Magento\Framework\Event\Observer $observer */
                $observer = $this->objectManager->get('\Magento\Framework\Event\Observer');
                $observer->setOrder($order);

                try {
                    $this->orderSaveAfter->execute($observer);
                } catch (\Exception $e) {
                    print("ERROR: Could not update order: {$e->getMessage()}\n");
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
        $this->setDescription("Update orders");
        parent::configure();
    }
}
