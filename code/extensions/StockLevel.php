<?php

class StreakStockLevel_StockLevelExtension extends GridSheetModelExtension {

    const ModelClass = 'StreakStockLevel';

    public function provideGridSheetData($modelClass, $isRelated) {
        if ($modelClass == static::ModelClass) {
            return StreakStockLevel::get();
        }
    }

    public function provideEditableColumns(array &$fieldSpecs) {
        xdebug_break();
        if (self::ModelClass == $this->owner->class) {
            $fieldSpecs += array(
                'ProductID' => array(
                    'title' => 'Product Name',
                    'callback' => function ($record) {
                        return new Select2Field(
                            'ProductID',
                            '',
                            Product::get()->map()->toArray()
                        );
                    }
                ),
                'StockLevel' => array(
                    'title' => 'In Stock',
                    'callback' => function ($record, $col) {
                        return new NumericField(
                            'StockLevel'
                        );
                    }
                )
            );
        }
    }

    /**
     * Called when a grid sheet is displaying a model related to another model. e.g. as a grid for a models ItemEditForm
     * in ModelAdmin.
     *
     * @param $relatedModelClass
     * @param array $relatedID
     * @param array $fieldSpecs
     * @return mixed
     */
    public function provideRelatedEditableColumns($relatedModelClass, $relatedID, array &$fieldSpecs) {

    }

    /**
     * Called for each new row in a grid when it is saved.
     *
     * @param $record
     * @return bool
     */
    public function gridSheetHandleNewRow(array &$record) {
        $updateData = $this->getUpdateColumns(
            $this->owner->class,
            $record
        );
        $stockLevel = StreakStockLevel::create($updateData);
        $stockLevel->write();

    }

    /**
     * Called to each existing row in a grid when it is saved.
     *
     * @param $record
     * @return bool
     */
    public function gridSheetHandleExistingRow(array &$record) {
        $updateData = $this->getUpdateColumns(
            $this->owner->class,
            $record
        );
        $this->owner->update($updateData);
    }
}