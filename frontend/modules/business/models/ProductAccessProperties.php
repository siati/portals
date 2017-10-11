<?php

namespace frontend\modules\business\models;

use Yii;

/**
 * This is the model class for table "{{%product_access_properties}}".
 *
 * @property integer $id
 * @property string $property
 * @property string $name
 * @property string $table
 * @property string $column
 * @property string $model_class
 * @property string $attribute
 * @property string $active
 * @property string $created_by
 * @property string $created_at
 * @property string $modified_by
 * @property string $modified_at
 */
class ProductAccessProperties extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_access_properties}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['property', 'name', 'table', 'model_class', 'created_by', 'created_at'], 'required'],
            [['active'], 'string'],
            [['created_at', 'modified_at'], 'safe'],
            [['property', 'created_by', 'modified_by'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 40],
            [['table', 'column', 'model_class', 'attribute'], 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'property' => Yii::t('app', 'Property'),
            'name' => Yii::t('app', 'Property Name'),
            'table' => Yii::t('app', 'Related Table'),
            'column' => Yii::t('app', 'Column Name'),
            'model_class' => Yii::t('app', 'Related Model Class'),
            'attribute' => Yii::t('app', 'Model Attribute'),
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
    public static function find()
    {
        return new \frontend\modules\business\activeQueries\ProductAccessPropertiesQuery(get_called_class());
    }
}
