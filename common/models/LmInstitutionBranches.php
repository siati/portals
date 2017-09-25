<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%lm_institution_branches}}".
 *
 * @property string $RECID
 * @property string $INSTITUTIONCODE
 * @property string $INSTITUTIONBRANCHCODE
 * @property string $INSTITUTIONBRANCHNAME
 * @property integer $ACTIVE
 */
class LmInstitutionBranches extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%lm_institution_branches}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['RECID', 'INSTITUTIONCODE', 'INSTITUTIONBRANCHCODE', 'INSTITUTIONBRANCHNAME', 'ACTIVE'], 'required'],
            [['RECID', 'ACTIVE'], 'integer'],
            [['INSTITUTIONCODE'], 'string', 'max' => 30],
            [['INSTITUTIONBRANCHCODE'], 'string', 'max' => 10],
            [['INSTITUTIONBRANCHNAME'], 'string', 'max' => 120],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'RECID' => Yii::t('app', 'RECID'),
            'INSTITUTIONCODE' => Yii::t('app', 'Institution Code'),
            'INSTITUTIONBRANCHCODE' => Yii::t('app', 'Branch Code'),
            'INSTITUTIONBRANCHNAME' => Yii::t('app', 'Branch Name'),
            'ACTIVE' => Yii::t('app', 'Active'),
        ];
    }

    /**
     * @inheritdoc
     * @return \common\activeQueries\LmInstitutionBranchesQuery the active query used by this AR class.
     */
    public static function find() {
        return new \common\activeQueries\LmInstitutionBranchesQuery(get_called_class());
    }
    
    /**
     * 
     * @param string $institution_code institution type
     * @param integer $active active
     * @return LmInstitutionBranches models
     */
    public static function searchInstitutions($institution_code, $active) {
        return static::find()->searchInstitutions($institution_code, $active);
    }
    
    /**
     * 
     * @param string $institution_code institution type
     * @param integer $active active
     * @return array institution branches
     */
    public static function branches($institution_code, $active) {
        return StaticMethods::modelsToArray(static::searchInstitutions($institution_code, $active), 'INSTITUTIONBRANCHCODE', 'INSTITUTIONBRANCHNAME', true);
    }

}
