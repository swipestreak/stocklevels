<?php
class StreakStockLevel_Module extends Object {
    const DefaultStockLevel = 10;

    private static $default_stock_level = self::DefaultStockLevel;

    public static function default_stock_level() {
        return ShopConfig::current_shop_config()->DefaultStockLevel ?:  self::config()->get('default_stock_level');
    }
}