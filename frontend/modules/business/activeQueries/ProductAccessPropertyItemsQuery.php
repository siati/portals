<?php

namespace frontend\modules\business\activeQueries;

use frontend\modules\business\models\ProductAccessPropertyItems;

/**
 * This is the ActiveQuery class for [[\frontend\modules\business\models\ProductAccessPropertyItems]].
 *
 * @see \frontend\modules\business\models\ProductAccessPropertyItems
 */
class ProductAccessPropertyItemsQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \frontend\modules\business\models\ProductAccessPropertyItems[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\business\models\ProductAccessPropertyItems|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @param integer $application application id
     * @param string $property access property
     * @param string $min_value minimum value
     * @param string $max_value maximum value
     * @param stgring $specific_values comma separated values
     * @param integer $required required: 1 - yes, 0 - no
     * @param integer $active active: 1 - yes, 0 - no
     * @param string $oneOrAll one or all
     * @return ProductAccessPropertyItems model(s)
     */
    public function searchItems($application, $property, $min_value, $max_value, $specific_values, $required, $active, $oneOrAll) {
        return $this->where(
                        'id > 0' .
                        (empty($application) ? '' : " && application = '$application'") .
                        (empty($property) ? '' : " && property = '$property'") .
                        (is_null($min_value) || $min_value == '' ? '' : " && '$min_value' >= min_value && (max_value is null || max_value = '' || '$min_value' <= max_value)") .
                        (is_null($max_value) || $max_value == '' ? '' : " && '$max_value' <= max_value && (min_value is null || min_value = '' || '$max_value' >= min_value)") .
                        (empty($specific_values) ? '' : " && (specific_values = '$specific_values' || specific_values like '$specific_values,%' || specific_values like '%,$specific_values,%' || specific_values like '%,$specific_values')") .
                        (is_numeric($required) ? " && required = '$required'" : '') .
                        (is_numeric($active) ? " && active = '$active'" : '')
                )->$oneOrAll();
    }

}
