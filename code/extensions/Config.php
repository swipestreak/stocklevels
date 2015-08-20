<?php

class StreakStockLevel_ConfigExtension extends CrackerJackDataExtension implements StreakConfigInterface {
    private static $db = array(
        'DefaultStockLevel' => 'Int'
    );

    public function provideStreakConfig(FieldList $fields) {
        $fields->push(
            new NumericField(
                'DefaultStockLevel',
                $this->fieldLabel('DefaultStockLevel'),
                StreakStockLevel_Module::default_stock_level()
            )
        );
    }
}