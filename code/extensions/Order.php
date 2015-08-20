<?php
class StreakStockLevel_OrderExtension extends CrackerJackDataExtension {
    /**
     * Called by extension by Order after payment is made.
     * @param $difference
     */
    public function onAfterPayment() {
        /** @var Order $order */
        $order = $this->owner;

        if (Order::STATUS_PROCESSING == $order->PaymentStatus) {
            foreach ($order->Items() as $item) {
                $product = $item->Product();
                $quantity = $item->Quantity;

                $stockLevel = $product->StockLevel();

                $stockLevel->StockLevel -= $quantity;
                $stockLevel->write();
            }
        }
    }

}