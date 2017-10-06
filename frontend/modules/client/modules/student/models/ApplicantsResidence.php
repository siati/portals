<?php

namespace frontend\modules\client\modules\student\models;

use Yii;
use common\models\StaticMethods;

/**
 * This is the model class for table "{{%applicants_residence}}".
 *
 * @property integer $id
 * @property integer $applicant
 * @property integer $county
 * @property integer $sub_county
 * @property integer $constituency
 * @property integer $ward
 * @property string $location
 * @property string $sub_location
 * @property string $village
 * @property string $apartment
 * @property string $nearest_primary
 * @property string $created_by
 * @property string $created_at
 * @property string $modified_by
 * @property string $modified_at
 */
class ApplicantsResidence extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%applicants_residence}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['applicant', 'county', 'constituency', 'ward', 'location', 'sub_location', 'village', 'apartment', 'nearest_primary', 'created_by'], 'required'],
            [['applicant', 'county', 'sub_county', 'constituency', 'ward'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['location', 'sub_location', 'village', 'created_by', 'modified_by'], 'string', 'min' => 3, 'max' => 20],
            [['apartment'], 'string', 'min' => 8, 'max' => 30],
            [['nearest_primary'], 'string', 'min' => 10, 'max' => 40],
            [['location', 'sub_location', 'village', 'apartment', 'nearest_primary'], 'notNumerical'],
            [['location', 'sub_location', 'village', 'apartment', 'nearest_primary'], 'sanitizeString'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'applicant' => Yii::t('app', 'Applicant'),
            'county' => Yii::t('app', 'County'),
            'sub_county' => Yii::t('app', 'Sub County'),
            'constituency' => Yii::t('app', 'Constituency'),
            'ward' => Yii::t('app', 'Ward'),
            'location' => Yii::t('app', 'Location'),
            'sub_location' => Yii::t('app', 'Sub Location'),
            'village' => Yii::t('app', 'Village / Estate'),
            'apartment' => Yii::t('app', 'Apartment Name / House Number'),
            'nearest_primary' => Yii::t('app', 'Nearest Public Primary School'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\activeQueries\ApplicantsResidenceQuery the active query used by this AR class.
     */
    public static function find() {
        return new \frontend\modules\client\modules\student\activeQueries\ApplicantsResidenceQuery(get_called_class());
    }
    
    /**
     * 
     * @param integer $pk residence id
     * @return ApplicantsResidence model
     */
    public static function returnResidence($pk) {
        return static::find()->byPk($pk);
    }
    
    /**
     * 
     * @param integer $applicant applicant id
     * @param integer $county county
     * @param integer $sub_county sub county
     * @param integer $constituency constituency
     * @param integer $ward ward
     * @param string $location location
     * @param string $sub_location sub location
     * @param string $village village
     * @param string $apartment apartment
     * @param string $nearest_primary nearest primary
     * @param string $oneOrAll one or all
     * @return ApplicantsResidence model[s]
     */
    public static function searchResidences($applicant, $county, $sub_county, $constituency, $ward, $location, $sub_location, $village, $apartment, $nearest_primary, $oneOrAll) {
        return static::find()->searchResidences($applicant, $county, $sub_county, $constituency, $ward, $location, $sub_location, $village, $apartment, $nearest_primary, $oneOrAll);
    }
    
    /**
     * 
     * @param integer $applicant applicant id
     * @return ApplicantsResidence model
     */
    public static function forApplicant($applicant) {
        return static::searchResidences($applicant, null, null, null, null, null, null, null, null, null, self::one);
    }
    
    /**
     * 
     * @param integer $applicant applicant id
     * @return ApplicantsResidence model
     */
    public static function newResidence($applicant) {
        $model = new ApplicantsResidence;
        
        $model->applicant = $applicant;
        
        $model->created_by = Yii::$app->user->identity->username;
        
        return $model;
    }
    
    /**
     * 
     * @param integer $id residence id
     * @param integer $applicant applicant id
     * @return ApplicantsResidence model
     */
    public static function residenceToLoad($id, $applicant) {
        return is_object($model = static::returnResidence($id)) || is_object($model = static::forApplicant($applicant)) ? $model : static::newResidence($applicant);
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
