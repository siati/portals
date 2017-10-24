<?php

namespace frontend\modules\business\models;

use Yii;
use common\models\StaticMethods;

/**
 * This is the model class for table "{{%product_access_property_items}}".
 *
 * @property integer $id
 * @property integer $application
 * @property integer $property
 * @property string $min_value
 * @property string $max_value
 * @property string $specific_values
 * @property string $remark
 * @property string $required
 * @property string $active
 * @property string $created_by
 * @property string $created_at
 * @property string $modified_by
 * @property string $modified_at
 */
class ProductAccessPropertyItems extends \yii\db\ActiveRecord {

    const active = 1;
    const not_active = 0;
    const required = 1;
    const not_required = 0;
    const comma = ',';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%product_access_property_items}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['application', 'property', 'created_by', 'created_at'], 'required'],
            [['application', 'property'], 'integer'],
            [['specific_values', 'remark', 'required', 'active'], 'string'],
            [['active'], 'newRuleMustBeActive'],
            [['created_at', 'modified_at'], 'safe'],
            [['created_by', 'modified_by'], 'string', 'max' => 20],
            [['min_value', 'max_value'], 'string', 'min' => 1, 'max' => 60],
            [['min_value', 'max_value', 'specific_values', 'remark'], 'sanitizeString'],
            ['remark', 'notNumerical'],
            ['remark', 'string', 'min' => 10],
        ];
    }
    
    /**
     * 
     * new rules must be active
     */
    public function newRuleMustBeActive() {
        $this->isNewRecord && $this->active != self::active ? $this->addError('active', 'New rule must be active so as to be honored') : $this->activeRuleMustHaveValues();
    }
    
    /**
     * 
     * active rule must have values
     */
    public function activeRuleMustHaveValues() {
        if ($this->active == self::active && empty($this->min_value) && empty($this->max_value) && (empty($this->specific_values) || str_replace(self::comma, '', $this->specific_values) == '')) {
                $this->addError('min_value', 'Either Minimum Value or Maximum Value or Specific Values is required');
                $this->addError('max_value', 'Either Minimum Value or Maximum Value or Specific Values is required');
                $this->addError('specific_values', 'Either Minimum Value or Maximum Value or Specific Values is required');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'application' => Yii::t('app', 'Application'),
            'property' => Yii::t('app', 'Property'),
            'min_value' => Yii::t('app', 'Minimum Value'),
            'max_value' => Yii::t('app', 'Maximum Value'),
            'specific_values' => Yii::t('app', 'Specific Values'),
            'remark' => Yii::t('app', 'Remark'),
            'required' => Yii::t('app', 'Required'),
            'active' => Yii::t('app', 'Active'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\business\activeQueries\ProductAccessPropertyItemsQuery the active query used by this AR class.
     */
    public static function find() {
        return new \frontend\modules\business\activeQueries\ProductAccessPropertyItemsQuery(get_called_class());
    }

    /**
     * 
     * @param integer $pk item id
     * @return ProductAccessPropertyItems model
     */
    public static function returnItem($pk) {
        return static::find()->byPk($pk);
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
    public static function searchItems($application, $property, $min_value, $max_value, $specific_values, $required, $active, $oneOrAll) {
        return static::find()->searchItems($application, $property, $min_value, $max_value, $specific_values, $required, $active, $oneOrAll);
    }

    /**
     * 
     * @param integer $application application id
     * @param string $property access property
     * @param integer $active active: 1 - yes, 0 - no
     * @param string $oneOrAll one or all
     * @return ProductAccessPropertyItems model(s)
     */
    public static function forApplicationAndProperty($application, $property, $active, $oneOrAll) {
        return static::searchItems($application, $property, null, null, null, null, $active, $oneOrAll);
    }

    /**
     * 
     * @param integer $application application id
     * @param string $property access property
     * @return ProductAccessPropertyItems model
     */
    public static function byApplicationAndProperty($application, $property) {
        return static::forApplicationAndProperty($application, $property, null, self::one);
    }

    /**
     * 
     * @param integer $application application id
     * @param string $property access property
     * @return ProductAccessPropertyItems model
     */
    public static function newItem($application, $property) {
        $model = new ProductAccessPropertyItems;

        $model->application = $application;
        $model->property = $property;

        $model->active = self::not_active;

        $model->required = self::required;

        $model->created_by = Yii::$app->user->identity->username;

        return $model;
    }

    /**
     * 
     * @param integer $id item id
     * @param integer $application application id
     * @param string $property access property
     * @return ProductAccessPropertyItems model
     */
    public static function itemToLoad($id, $application, $property) {
        return is_object($model = static::returnItem($id)) || (!empty($application) && !empty($property) && is_object($model = static::byApplicationAndProperty($application, $property))) ? $model : static::newItem($application, $property);
    }

    /**
     * 
     * @return boolean true - model saved
     */
    public function modelSave() {
        if ($this->isNewRecord)
            $this->created_at = StaticMethods::now();
        else {
            $this->modified_by = Yii::$app->user->identity->username;
            $this->modified_at = StaticMethods::now();
        }

        return $this->save();
    }
    
    /**
     * 
     * @param string $value value being evaluated
     * @return boolean true - value is included in this property item matrix
     */
    public function valueIsIncluded($value) {
        return static::valueIsContained($this->min_value, $this->max_value, $this->specific_values, $value);
    }

    /**
     * 
     * @param string $min_value minimum value
     * @param string $max_value maximum value
     * @param string $value_array comma separated values without spaces between the values
     * @param string $value value being evaluated
     * @return boolean true - value is contained within the supplied matrix
     */
    public static function valueIsContained($min_value, $max_value, $value_array, $value) {
        $start = $value . self::comma;
        
        $middle = self::comma . $value . self::comma;
        
        $end = self::comma . $value;
        
        return  
                $value != null && $value != '' && 
                
                (
                    ($min_value != null && $min_value != '' && $max_value != null && $max_value != '' && $value >= $min_value && $value <= $max_value) ||
                    ($min_value != null && $min_value != '' && ($max_value == null || $max_value == '') && $value >= $min_value) ||
                    ($max_value != null && $max_value != '' && ($min_value == null || $min_value == '') && $value <= $max_value) ||
                    (
                        $value_array != null && $value_array != '' && str_replace(self::comma, '', $value_array) != '' &&
                        (
                            $value == $value_array ||
                            (is_numeric($left = stripos($value_array, $start)) && $left == 0) ||
                            stripos($value_array, $middle) !== false ||
                            (strlen($value_array) >= strlen($end) && is_numeric($right = stripos($value_array, $end)) && $right == strlen($value_array) - strlen($end))
                        )
                    )
                );
    }

    /**
     * 
     * @return array active
     */
    public static function actives() {
        return [self::active => 'Active', self::not_active => 'Inactive'];
    }

    /**
     * 
     * @return array required
     */
    public static function requireds() {
        return [self::required => 'Required', self::not_required => 'Optional'];
    }

}
