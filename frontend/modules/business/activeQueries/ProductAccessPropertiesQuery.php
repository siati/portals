<?php

namespace frontend\modules\business\activeQueries;
use frontend\modules\business\models\ProductAccessProperties;

/**
 * This is the ActiveQuery class for [[\frontend\modules\business\models\ProductAccessProperties]].
 *
 * @see \frontend\modules\business\models\ProductAccessProperties
 */
class ProductAccessPropertiesQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \frontend\modules\business\models\ProductAccessProperties[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\business\models\ProductAccessProperties|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @param string $property property
     * @param string $name property name
     * @param string $table related table
     * @param string $column column from [[$table]]
     * @param string $model_class corresponding model class for [[$table]]
     * @param string $attribute attribute of [[$model_class]]
     * @param integer $active active or inactive
     * @param string $oneOrAll one or all
     * @return ProductAccessProperties ActiveRecord(s)
     */
    public function searchProperties($property, $name, $table, $column, $model_class, $attribute, $active, $oneOrAll) {
        return $this->where(
                        'id > 0' .
                        (empty($property) ? '' : " && property = '$property'") .
                        (empty($name) ? '' : " && name like '%$name%'") .
                        (empty($table) ? '' : " && table = '$table'") .
                        (empty($column) ? '' : " && column = '$column'") .
                        (empty($model_class) ? '' : " && model_class = '$model_class'") .
                        (empty($attribute) ? '' : " && attribute = '$attribute'") .
                        (is_numeric($property) ? " && active = '$active'" : '')
                )->orderBy('name asc')->$oneOrAll();
    }

}
