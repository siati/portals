<?php

namespace frontend\modules\business\models;

use Yii;

/**
 * This is the model class for table "{{%defectivity_checks}}".
 *
 * @property integer $id
 * @property integer $application
 * @property string $is_appeal
 * @property integer $defectivity
 * @property string $active
 * @property string $checked
 * @property string $created_by
 * @property string $created_at
 * @property string $modified_by
 * @property string $modified_at
 */
class DefectivityChecks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%defectivity_checks}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['application', 'defectivity', 'created_by'], 'required'],
            [['application', 'defectivity'], 'integer'],
            [['is_appeal', 'active', 'checked'], 'string'],
            [['created_at', 'modified_at'], 'safe'],
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
            'application' => Yii::t('app', 'Application'),
            'is_appeal' => Yii::t('app', 'Is Appeal'),
            'defectivity' => Yii::t('app', 'Defectivity'),
            'active' => Yii::t('app', 'Is Active'),
            'checked' => Yii::t('app', 'Checked'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\business\activeQueries\DefectivityChecksQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \frontend\modules\business\activeQueries\DefectivityChecksQuery(get_called_class());
    }
}
