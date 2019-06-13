<?php


namespace Dealer4Dealer\SubstituteOrders\Observer\Sales;

use Magento\Framework\Exception\LocalizedException;

class OrderShipmentTrackSaveAfter implements \Magento\Framework\Event\ObserverInterface
{

    /** @var \Dealer4Dealer\SubstituteOrders\Api\ShipmentRepositoryInterface  */
    protected $shipmentRepo;

    public function __construct(
        \Dealer4Dealer\SubstituteOrders\Api\ShipmentRepositoryInterface $shipmentRepo
    ) {
        $this->shipmentRepo = $shipmentRepo;
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $track = $observer->getTrack();
        try {
            $shipment = $track->getShipment();
        } catch (\Exception $e) {
            return;
        }

        try {
            /* @var $substitute \Dealer4Dealer\SubstituteOrders\Api\Data\ShipmentInterface */
            $subShipment = $this->shipmentRepo->getByIncrementId($shipment->getIncrementId());
        } catch (LocalizedException $e) {
            return;
        }

        # update all trackers
        $trackers = [];
        foreach ($shipment->getTracks() as $track) {
            $trackers[] = new \Dealer4Dealer\SubstituteOrders\Model\ShipmentTracking(
                $track->getTitle(),
                $track->getTrackNumber()
            );
        }
        $subShipment->setTracking($trackers);
        $subShipment->save();
    }
}
