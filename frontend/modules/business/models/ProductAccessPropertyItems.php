<?php

namespace frontend\modules\business\models;

use Yii;

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
class ProductAccessPropertyItems extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_access_property_items}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
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
    public function attributeLabels()
    {
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
    public static function find()
    {
        return new \frontend\modules\business\activeQueries\ProductAccessPropertyItemsQuery(get_called_class());
    }
}
