<?php

class StreakStockLevel extends DataObject {
    private static $db = array(
        'StockLevel' => 'Int'
    );
    private static $has_one = array(
        'Product' => 'Product'              // TODO Should be able to extend more than product
    );
    private static $summary_fields = array(
        'Product.Title' => 'Product',
        'StockLevel' => 'StockLevel'
    );
    private static $singular_name = 'Stock Level';

}