<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%lm_institution}}".
 *
 * @property string $RECID
 * @property string $COUNTRY
 * @property integer $INSTITUTIONTYPE
 * @property integer $SCHOOLTYPE
 * @property string $INSTITUTIONNAME
 * @property string $INSTITUTIONCODE
 * @property integer $ACTIVE
 */
class LmInstitution extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%lm_institution}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['RECID', 'COUNTRY', 'INSTITUTIONTYPE', 'SCHOOLTYPE', 'INSTITUTIONNAME', 'INSTITUTIONCODE', 'ACTIVE'], 'required'],
            [['RECID', 'INSTITUTIONTYPE', 'SCHOOLTYPE', 'ACTIVE'], 'integer'],
            [['COUNTRY'], 'string', 'max' => 10],
            [['INSTITUTIONNAME'], 'string', 'max' => 100],
            [['INSTITUTIONCODE'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'RECID' => Yii::t('app', 'RECID'),
            'COUNTRY' => Yii::t('app', 'Country'),
            'INSTITUTIONTYPE' => Yii::t('app', 'Institution Type'),
            'SCHOOLTYPE' => Yii::t('app', 'School Type'),
            'INSTITUTIONNAME' => Yii::t('app', 'Institution Name'),
            'INSTITUTIONCODE' => Yii::t('app', 'Institution Code'),
            'ACTIVE' => Yii::t('app', 'Active'),
        ];
    }

    /**
     * @inheritdoc
     * @return \common\activeQueries\LmInstitutionQuery the active query used by this AR class.
     */
    public static function find() {
        return new \common\activeQueries\LmInstitutionQuery(get_called_class());
    }

    /**
     * 
     * @param string $country country
     * @param string $institution_type institution type
     * @param string $school_type school type
     * @param integer $active active
     * @return LmInstitution models
     */
    public static function searchInstitutions($country, $institution_type, $school_type, $active) {
        return static::find()->searchInstitutions($country, $institution_type, $school_type, $active);
    }

    /**
     * 
     * @param string $country country
     * @param string $institution_type institution type
     * @param string $school_type school type
     * @param integer $active active
     * @return array models
     */
    public static function institutions($country, $institution_type, $school_type, $active) {
        return StaticMethods::modelsToArray(static::searchInstitutions($country, $institution_type, $school_type, $active), 'INSTITUTIONCODE', 'INSTITUTIONNAME', true);
    }

}
