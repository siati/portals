<?php

namespace frontend\modules\client\modules\student\models;

use Yii;
use common\models\StaticMethods;

/**
 * This is the model class for table "{{%applicant_sponsors}}".
 *
 * @property integer $id
 * @property integer $applicant
 * @property integer $relationship
 * @property string $name
 * @property integer $phone
 * @property string $email
 * @property integer $postal_no
 * @property integer $postal_code
 * @property integer $study_level
 * @property string $created_by
 * @property string $created_at
 * @property string $modified_by
 * @property string $modified_at
 */
class ApplicantSponsors extends \yii\db\ActiveRecord {

    const relationship_relative = 0;
    const relationship_guardian = 1;
    const relationship_institution = 2;
    const study_level_primary = 0;
    const study_level_secondary = 1;
    const study_level_primary_secondary = 2;
    const study_level_tertiary = 3;
    const study_level_primary_secondary_tertiary = 4;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%applicant_sponsors}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['applicant', 'relationship', 'name', 'study_level', 'created_by'], 'required'],
            [['applicant', 'postal_no', 'postal_code'], 'integer'],
            [['name', 'phone', 'email', 'postal_no'], 'sanitizeString'],
            [['name', 'email'], 'notNumerical'],
            [['phone', 'postal_no'], 'positiveValue'],
            [['created_at', 'modified_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['phone'], 'string', 'min' => 9, 'max' => 13],
            [['phone'], 'kenyaPhoneNumber'],
            [['phone'], 'required',
                'when' => function() {
                    return empty($this->email) && empty($this->postal_no) && empty($this->postal_code);
                },
                'whenClient' => "
                    function (attribute, value) {
                        $('#applicantsponsors-email, #applicantsponsors-postal_no, #applicantsponsors-postal_code').blur();
                        return ($('#applicantsponsors-email').val() === null || $('#applicantsponsors-email').val() === '') && ($('#applicantsponsors-postal_no').val() === null || $('#applicantsponsors-postal_no').val() === '') && ($('#applicantsponsors-postal_code').val() === null || $('#applicantsponsors-postal_code').val() === '');
                    } 
                ",
                'message' => 'Phone No, Email. or Postal Address must be provided'
            ],
            [['email'], 'required',
                'when' => function() {
                    return empty($this->phone) && empty($this->postal_no) && empty($this->postal_code);
                },
                'whenClient' => "
                    function (attribute, value) {
                        $('#applicantsponsors-phone, #applicantsponsors-postal_no, #applicantsponsors-postal_code').blur();
                        return ($('#applicantsponsors-phone').val() === null || $('#applicantsponsors-phone').val() === '') && ($('#applicantsponsors-postal_no').val() === null || $('#applicantsponsors-postal_no').val() === '') && ($('#applicantsponsors-postal_code').val() === null || $('#applicantsponsors-postal_code').val() === '');
                    } 
                ",
                'message' => 'Phone No., Email or Postal Address must be provided'
            ],
            [['postal_no', 'postal_code'], 'fullPostalAddress'],
            [['postal_no', 'postal_code'], 'required',
                'when' => function() {
                    return empty($this->phone) && empty($this->email);
                },
                'whenClient' => "
                    function (attribute, value) {
                        $('#applicantsponsors-phone, #applicantsponsors-postal_no, #applicantsponsors-postal_code').blur();
                        return ($('#applicantsponsors-phone').val() === null || $('#applicantsponsors-phone').val() === '') && ($('#applicantsponsors-email').val() === null || $('#applicantsponsors-email').val() === '');
                    } 
                ",
                'message' => 'Phone No., Email or Postal Address must be provided'
            ],
            ['email', 'string', 'min' => 5, 'max' => 60],
            ['email', 'email'],
            ['email', 'toLowerCase'],
            [['postal_no'], 'string', 'max' => 6],
            [['created_by', 'modified_by'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'applicant' => Yii::t('app', 'Applicant'),
            'relationship' => Yii::t('app', 'Relationship'),
            'name' => Yii::t('app', 'Name'),
            'phone' => Yii::t('app', 'Phone'),
            'email' => Yii::t('app', 'Email'),
            'postal_no' => Yii::t('app', 'Postal No.'),
            'postal_code' => Yii::t('app', 'Postal Code'),
            'study_level' => Yii::t('app', 'Study Level'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\activeQueries\ApplicantSponsorsQuery the active query used by this AR class.
     */
    public static function find() {
        return new \frontend\modules\client\modules\student\activeQueries\ApplicantSponsorsQuery(get_called_class());
    }

    /**
     * 
     * @param integer $pk sponsor id
     * @return ApplicantSponsors model
     */
    public static function returnSponsor($pk) {
        return static::find()->byPk($pk);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @return ApplicantSponsors model
     */
    public static function sponsorsForApplicant($applicant) {
        return static::find()->sponsorsForApplicant($applicant);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @return ApplicantSponsors model
     */
    public static function newSponsor($applicant) {
        $model = new ApplicantSponsors;

        $model->applicant = $applicant;

        $model->created_by = Yii::$app->user->identity->username;

        return $model;
    }

    /**
     * 
     * @param integer $id sponsor id
     * @param integer $applicant applicant id
     * @return ApplicantSponsors model
     */
    public static function sponsorToLoad($id, $applicant) {
        return is_object($model = static::returnSponsor($id)) ? $model : static::newSponsor($applicant);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @return ApplicantSponsors models
     */
    public static function sponsorsToLoad($applicant) {
        return static::sponsorsForApplicant($applicant);
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
     * @return array relatioships
     */
    public static function relatioships() {
        return [
            self::relationship_relative => 'A Relative',
            self::relationship_guardian => 'A Guardian',
            self::relationship_institution => 'An Organization'
        ];
    }

    /**
     * 
     * @return array studyLevels
     */
    public static function studyLevels() {
        return [
            self::study_level_primary => 'Primary School',
            self::study_level_secondary => 'Secondary School',
            self::study_level_primary_secondary => 'Primary & Secondary School',
            self::study_level_tertiary => 'Tertiary Education',
            self::study_level_primary_secondary_tertiary => 'Primary / Secondary & Tertiary Education'
        ];
    }

}
