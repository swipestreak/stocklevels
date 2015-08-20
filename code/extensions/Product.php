<?php
class StreakStockLevel_ProductExtension extends GridSheetModelExtension {
    const OrderMadeStatus = 'Paid';
    const FieldName = 'StreakStockLevel';
    const ModelClass = 'Product';
    const RelationshipName = 'StreakStockLevel';
    const RelatedModelClass = 'StreakStockLevel';

    private static $has_one = array(
        self::RelationshipName => self::RelatedModelClass
    );

    private static $tab_name = 'Root.Main';

    public function updateCMSFields(FieldList $fields) {
        $fields->insertBefore(
            new NumericField(
                'StreakStockLevel_StockLevel',
                $this->fieldLabel('StockLevel', 'In Stock'),
                $this->StockLevel()
            ),
            'Content'
        );
    }
    public function onBeforeWrite() {
        $request = Controller::curr()->getRequest();

        if (!is_null($request->postVar('StreakStockLevel_StockLevel'))) {
            $relFieldName = self::RelationshipName . 'ID';

            if (!$stockLevel = StreakStockLevel::get()->filter('ProductID', $this->owner->ID)->first()) {
                $stockLevel = StreakStockLevel::create();
            }
            $stockLevel->StockLevel = $request->postVar('StreakStockLevel_StockLevel');
            $stockLevel->ProductID = $this->owner->ID;
            $stockLevel->write();

            $this->owner->$relFieldName = $stockLevel->ID;
        }
    }

    protected function ownerKeyField() {
        return $this()->class . 'ID';
    }


    public function StockLevel() {
        if ($this->owner->StreakStockLevelID) {
            $stockLevel = $this->owner->StreakStockLevel()->StockLevel;
            return $stockLevel;
        }
    }

    public function provideGridSheetData($modelClass, $isRelatedModel) {
        if (self::ModelClass == $modelClass && !$isRelatedModel) {
            return Product::get();
        }
    }

    /**
     * Called for each new row in a grid when it is saved.
     *
     * @param $row
     * @return bool
     */
    public function gridSheetHandleNewRow(array &$row) {
        $updateData = $this->getUpdateColumns(
            $this->owner->class,
            $row
        );
        $stockLevel = StreakStockLevel::create($updateData);

        $this->owner->StreakStockLevelID = $stockLevel->write();
    }

    /**
     * Called to each existing row in a grid when it is saved.
     *
     * @param $row
     * @return bool
     */
    public function gridSheetHandleExistingRow(array &$row) {
        $updateData = $this->getUpdateColumns(
            $this->owner->class,
            $row
        );

        if (!$stockLevel = StreakStockLevel::get()->byID($this->owner->StreakStockLevelID)) {
            $stockLevel = StreakStockLevel::create();
        }
        $stockLevel->ProductID = $this->owner->ID;
        $stockLevel->update($updateData);

        $this->owner->StreakStockLevelID = $stockLevel->write();
    }

    public function provideEditableColumns(array &$fieldSpecs) {
        $fieldSpecs += array(
            'StockLevel' => array(
                'title' => 'In Stock',
                'callback' => function($record) {
                    $inStock = 0;
                    if ($stockLevel = StreakStockLevel::get()->byID($record->StreakStockLevelID)) {
                        $inStock = $stockLevel->StockLevel;
                    }
                    return new NumericField(
                        'StockLevel',
                        'Stock level',
                        $inStock
                    );
                }
            )
        );
        return true;
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
        // no relations
    }

}