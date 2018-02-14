<?php

namespace frontend\modules\business\models;

use Yii;

/**
 * This is the model class for table "{{%defectivities}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $active
 * @property string $description
 * @property string $created_by
 * @property string $created_at
 * @property string $modified_by
 * @property string $modified_at
 */
class Defectivities extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%defectivities}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name', 'created_by'], 'required'],
            [['active', 'description'], 'string'],
            [['created_at', 'modified_at'], 'safe'],
            [['code'], 'string', 'max' => 10],
            [['name'], 'string', 'max' => 128],
            [['created_by', 'modified_by'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Defectivity Code'),
            'name' => Yii::t('app', 'Defectivity Name'),
            'active' => Yii::t('app', 'Active'),
            'description' => Yii::t('app', 'Description'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\business\activeQueries\DefectivitiesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \frontend\modules\business\activeQueries\DefectivitiesQuery(get_called_class());
    }
}
