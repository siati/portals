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
 * @property string $item
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
            [['application', 'property', 'item', 'created_by', 'created_at'], 'required'],
            [['application', 'property'], 'integer'],
            [['specific_values', 'remark', 'required', 'active'], 'string'],
            [['created_at', 'modified_at'], 'safe'],
            [['item', 'created_by', 'modified_by'], 'string', 'max' => 20],
            [['min_value', 'max_value'], 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'application' => Yii::t('app', 'Application'),
            'property' => Yii::t('app', 'Property'),
            'item' => Yii::t('app', 'Item'),
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
     * @param string $item item in [[$property]]
     * @param string $min_value minimum value
     * @param string $max_value maximum value
     * @param stgring $specific_values comma separated values
     * @param integer $required required: 1 - yes, 0 - no
     * @param integer $active active: 1 - yes, 0 - no
     * @param string $oneOrAll one or all
     * @return ProductAccessPropertyItems model(s)
     */
    public static function searchItems($application, $property, $item, $min_value, $max_value, $specific_values, $required, $active, $oneOrAll) {
        return static::find()->searchItems($application, $property, $item, $min_value, $max_value, $specific_values, $required, $active, $oneOrAll);
    }
    
    /**
     * 
     * @param integer $application application id
     * @param string $property access property
     * @param string $item item in [[$property]]
     * @param integer $active active: 1 - yes, 0 - no
     * @param string $oneOrAll one or all
     * @return ProductAccessPropertyItems model(s)
     */
    public static function forApplicationPropertyAndItem($application, $property, $item, $active, $oneOrAll) {
        return static::searchItems($application, $property, $item, null, null, null, null, $active, $oneOrAll);
    }
    
    /**
     * 
     * @param integer $application application id
     * @param string $property access property
     * @param string $item item in [[$property]]
     * @return ProductAccessPropertyItems model
     */
    public static function byApplicationPropertyAndItem($application, $property, $item) {
        return static::forApplicationPropertyAndItem($application, $property, $item, null, self::one);
    }
    
    /**
     * 
     * @param integer $application application id
     * @param string $property access property
     * @param string $item item in [[$property]]
     * @return ProductAccessPropertyItems model
     */
    public static function newItem($application, $property, $item) {
        $model = new ProductAccessPropertyItems;
        
        $model->application = $application;
        $model->property = $property;
        $model->item = $item;
        
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
     * @param string $item item in [[$property]]
     * @return ProductAccessPropertyItems model
     */
    public static function itemToLoad($id, $application, $property, $item) {
        return is_object($model = static::returnItem($id)) || is_object($model = static::byApplicationPropertyAndItem($application, $property, $item)) ? $model : static::newItem($application, $property, $item);
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

}
