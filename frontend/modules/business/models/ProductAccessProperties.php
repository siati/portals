<?php

namespace frontend\modules\business\models;

use Yii;
use common\models\StaticMethods;
/**
 * This is the model class for table "{{%product_access_properties}}".
 *
 * @property integer $id
 * @property string $property
 * @property string $name
 * @property string $table
 * @property string $column
 * @property \yii\db\ActiveRecord $model_class
 * @property string $attribute
 * @property string $operation
 * @property string $active
 * @property string $created_by
 * @property string $created_at
 * @property string $modified_by
 * @property string $modified_at
 */
class ProductAccessProperties extends \yii\db\ActiveRecord {

    const active = 1;
    const not_active = 0;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%product_access_properties}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['property', 'name', 'table', 'model_class', 'created_by'], 'required'],
            [['operation', 'active'], 'string'],
            [['created_at', 'modified_at'], 'safe'],
            [['created_by', 'modified_by'], 'string', 'max' => 20],
            [['property'], 'string', 'max' => 40],
            ['name', 'string', 'min' => 3, 'max' => 40],
            ['name', 'sanitizeString'],
            ['name', 'notNumerical'],
            [['table', 'column', 'attribute'], 'string', 'max' => 60],
            [['model_class'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'property' => Yii::t('app', 'Property'),
            'name' => Yii::t('app', 'Property Name'),
            'table' => Yii::t('app', 'Related Table'),
            'column' => Yii::t('app', 'Column Name'),
            'model_class' => Yii::t('app', 'Related Model Class'),
            'attribute' => Yii::t('app', 'Model Attribute'),
            'operation' => Yii::t('app', 'Operation'),
            'active' => Yii::t('app', 'Active'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\business\activeQueries\ProductAccessPropertiesQuery the active query used by this AR class.
     */
    public static function find() {
        return new \frontend\modules\business\activeQueries\ProductAccessPropertiesQuery(get_called_class());
    }

    /**
     * 
     * @param integer $pk property id
     * @return ProductAccessProperties model
     */
    public static function returnProperty($pk) {
        return static::find()->byPk($pk);
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
     * @return ProductAccessProperties model(s)
     */
    public static function searchProperties($property, $name, $table, $column, $model_class, $attribute, $active, $oneOrAll) {
        return static::find()->searchProperties($property, $name, $table, $column, $model_class, $attribute, $active, $oneOrAll);
    }

    /**
     * 
     * @param string $property property
     * @return ProductAccessProperties model
     */
    public static function byProperty($property) {
        return static::searchProperties($property, null, null, null, null, null, null, self::one);
    }

    /**
     * 
     * @param string $table related table
     * @param string $column column from [[$table]]
     * @return ProductAccessProperties model
     */
    public static function byTableAndColumn($table, $column) {
        return static::searchProperties(null, null, $table, $column, null, null, null, self::one);
    }

    /**
     * 
     * @param string $model_class corresponding model class for [[$table]]
     * @param string $attribute attribute of [[$model_class]]
     * @return ProductAccessProperties model
     */
    public static function byModelclassAndAttribute($model_class, $attribute) {
        return static::searchProperties(null, null, null, null, $model_class, $attribute, null, self::one);
    }

    /**
     * 
     * @param string $property property
     * @param string $table related table
     * @param string $column column from [[$table]]
     * @param string $model_class corresponding model class for [[$table]]
     * @param string $attribute attribute of [[$model_class]]
     * @return ProductAccessProperties model
     */
    public static function newProperty($property, $table, $column, $model_class, $attribute) {
        $model = new ProductAccessProperties;

        $model->property = $property;
        $model->table = $table;
        $model->column = $column;
        $model->model_class = $model_class;
        $model->attribute = $attribute;

        empty($model->table) || empty($model->column) ? ('') : ($model->operation = "$model->column " . \frontend\modules\client\modules\student\models\ApplicantProductAccessCheckers::equal_to);

        $model->active = self::not_active;

        $model->created_by = Yii::$app->user->identity->username;

        return $model;
    }

    /**
     * 
     * @param integer $id property id
     * @param string $property property
     * @param string $table related table
     * @param string $column column from [[$table]]
     * @param string $model_class corresponding model class for [[$table]]
     * @param string $attribute attribute of [[$model_class]]
     * @return ProductAccessProperties model
     */
    public static function propertyToLoad($id, $property, $table, $column, $model_class, $attribute) {
        return is_object($model = static::returnProperty($id)) || is_object($model = static::byProperty($property)) || (!empty($table) && !empty($column) && is_object($model = static::byTableAndColumn($table, $column))) || (!empty($model_class) && !empty($attribute) && is_object($model = static::byModelclassAndAttribute($model_class, $attribute))) ? $model : static::newProperty($property, $table, $column, $model_class, $attribute);
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
