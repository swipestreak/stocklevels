<?php
class StreakStockLevel_ProductFormExtension extends Extension {
    public function updateFields(FieldList $fields) {
/*
        if ($product = $this->getProduct()) {
            $stockLevel = $product->StockLevel();

            $fields->insertAfter(
                new LiteralField('StockLevel', '<span class="stock-level">' . $stockLevel . ' in stock</span>'),
                'Quantity'
            );
        }
*/
    }

    private function getProduct() {
        $request = Controller::curr()->getRequest();

        return Product::get_by_link($request->getURL());
    }

}