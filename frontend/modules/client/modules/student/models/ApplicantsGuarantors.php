<?php

namespace frontend\modules\client\modules\student\models;
use common\models\User;
use common\models\StaticMethods;

use Yii;

/**
 * This is the model class for table "{{%applicants_guarantors}}".
 *
 * @property integer $id
 * @property integer $applicant
 * @property string $fname
 * @property string $mname
 * @property string $lname
 * @property integer $yob
 * @property string $gender
 * @property integer $id_no
 * @property integer $phone
 * @property string $email
 * @property integer $postal_no
 * @property integer $postal_code
 * @property string $kra_pin
 * @property integer $county
 * @property integer $sub_county
 * @property integer $constituency
 * @property integer $ward
 * @property string $location
 * @property string $sub_location
 * @property string $village
 * @property string $occupation
 * @property integer $employed
 * @property string $staff_no
 * @property string $employer_name
 * @property integer $employer_phone
 * @property string $employer_email
 * @property integer $employer_postal_no
 * @property integer $employer_postal_code
 * @property string $created_by
 * @property string $created_at
 * @property string $modified_by
 * @property string $modified_at
 */
class ApplicantsGuarantors extends \yii\db\ActiveRecord {

    const employed_no = 0;
    const employed_yes = 1;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%applicants_guarantors}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['applicant', 'fname', 'yob', 'gender', 'id_no', 'county', 'sub_county', 'constituency', 'ward', 'occupation'], 'required'],
            [['applicant', 'yob', 'id_no', 'phone', 'postal_no', 'postal_code', 'county', 'sub_county', 'constituency', 'ward', 'employed', 'employer_postal_no', 'employer_postal_code'], 'integer'],
            [['gender'], 'string'],
            [['mname', 'lname'], 'required',
                'when' => function () {
                    return $this->middleOrLastNameRequired();
                },
                'whenClient' => "
                    function (attribute, value) {
                        return ($('#applicantsguarantors-mname').val() === null || $('#applicantsguarantors-mname').val() === '') && ($('#applicantsguarantors-lname').val() === null || $('#applicantsguarantors-lname').val() === '');
                    } 
                ",
                'message' => 'Middle or last name must be provided'
            ],
            [['phone', 'email'], 'required',
                'when' => function () {
                    return empty($this->phone) && empty($this->email);
                },
                'whenClient' => "
                    function (attribute, value) {
                        return ($('#applicantsguarantors-phone').val() === null || $('#applicantsguarantors-phone').val() === '') && ($('#applicantsguarantors-email').val() === null || $('#applicantsguarantors-email').val() === '');
                    }
                ",
                'message' => 'Guarantor\'s Phone No. or Email must be provided'
            ],
            [['kra_pin', 'staff_no', 'employer_name'], 'required',
                'when' => function () {
                    return $this->isFormallyEmployed();
                },
                'whenClient' => "
                    function (attribute, value) {
                        return $('#applicantsguarantors-employed').val() === '" . self::employed_yes . "';
                    } 
                "
            ],
            [['employer_phone', 'employer_email'], 'required',
                'when' => function () {
                    return $this->isFormallyEmployed() && empty($this->employer_phone) && empty($this->employer_email);
                },
                'whenClient' => "
                    function (attribute, value) {
                        return $('#applicantsguarantors-employed').val() === '" . self::employed_yes . "' && ($('#applicantsguarantors-employer_phone').val() === null || $('#applicantsguarantors-employer_phone').val() === '') && ($('#applicantsguarantors-employer_email').val() === null || $('#applicantsguarantors-employer_email').val() === '');
                    }
                ",
                'message' => 'Employer\'s Phone No. or Email must be provided'
            ],
            [['id_no', 'kra_pin'], 'notOwnParent'],
            [['id_no', 'kra_pin'], 'distinctDetails'],
            [['fname', 'mname', 'lname', 'location', 'sub_location', 'village', 'occupation', 'employer_name'], 'notNumerical'],
            [['fname', 'mname', 'lname'], 'string', 'min' => 3, 'max' => 20],
            [['id_no'], 'string', 'min' => 7, 'max' => 8],
            [['phone', 'employer_phone'], 'string', 'min' => 9, 'max' => 13],
            [['phone', 'employer_phone'], 'kenyaPhoneNumber'],
            [['email', 'employer_email'], 'string', 'max' => 40],
            [['email', 'employer_email'], 'email'],
            [['postal_no', 'employer_postal_no'], 'string', 'max' => 6],
            [['id_no', 'phone', 'postal_no', 'employer_postal_no', 'employer_phone'], 'positiveValue'],
            [['postal_no', 'postal_code'], 'fullPostalAddress'],
            [['employer_postal_no', 'employer_postal_code'], 'fullEmployerPostalAddress'],
            [['kra_pin'], 'string', 'max' => 11, 'length' => 11],
            ['kra_pin', 'toUpperCase'],
            [['kra_pin'], 'KRAPin'],
            [['location', 'sub_location', 'village', 'occupation'], 'string', 'min' => 2, 'max' => 30],
            [['staff_no'], 'string', 'min' => 3, 'max' => 10],
            [['employer_name'], 'string', 'min' => 4, 'max' => 45],
            [['fname', 'mname', 'lname', 'phone', 'email', 'location', 'sub_location', 'village', 'occupation', 'staff_no', 'employer_name', 'employer_phone', 'employer_email'], 'sanitizeString'],
            [['created_at', 'modified_at'], 'safe'],
            [['created_by', 'modified_by'], 'string', 'max' => 15],
        ];
    }

    /**
     * 
     * @return boolean true - guarantor's middle or last name is required
     */
    public function middleOrLastNameRequired() {
        return empty($this->mname) && empty($this->lname);
    }

    /**
     * 
     * @return boolean true - is formally employed
     */
    public function isFormallyEmployed() {
        return $this->employed == self::employed_yes;
    }

    /**
     * applicant should not pose a guarantor himself
     * 
     * @param string $attribute attribute of guarantor
     */
    public function notOwnParent($attribute) {
        if (is_object($user = User::notOwnSenior($attribute, $this->$attribute)) && is_object($applicant = Applicants::returnApplicant($user->id)))
            if ($applicant->id == $this->applicant)
                $this->addError($attribute, 'You\'re trying to be your own guarantor!');
            else
            if ($applicant->isMinor())
                $this->addError($attribute, 'Please confirm guarantor\'s year of birth!');
            else
            if ((!empty($user->id_no) && !empty($this->id_no) && $user->id_no != $this->id_no) || (!empty($user->kra_pin) && !empty($this->kra_pin) && $user->kra_pin != $this->kra_pin))
                $this->addError($attribute, 'Please confirm your guarantor\'s ID. No. or KRA PIN');
    }

    /**
     * applicant cannot reuse certain details
     * 
     * @param string $attribute attribute of guarantor
     */
    public function distinctDetails($attribute) {
        if (is_object(static::find()->distinctDetails($attribute, $this->$attribute, $this->id)))
            $this->addError($attribute, 'You seem to already have used this ' . $this->getAttributeLabel($attribute));
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'applicant' => Yii::t('app', 'Applicant'),
            'fname' => Yii::t('app', 'First Name'),
            'mname' => Yii::t('app', 'Middle Name'),
            'lname' => Yii::t('app', 'Last Name'),
            'yob' => Yii::t('app', 'Year of Birth'),
            'gender' => Yii::t('app', 'Gender'),
            'id_no' => Yii::t('app', 'National ID. No.'),
            'phone' => Yii::t('app', 'Phone No.'),
            'email' => Yii::t('app', 'Email'),
            'postal_no' => Yii::t('app', 'Postal No'),
            'postal_code' => Yii::t('app', 'Postal Code'),
            'kra_pin' => Yii::t('app', 'KRA PIN'),
            'county' => Yii::t('app', 'County'),
            'sub_county' => Yii::t('app', 'Sub County'),
            'constituency' => Yii::t('app', 'Constituency'),
            'ward' => Yii::t('app', 'Ward'),
            'location' => Yii::t('app', 'Location'),
            'sub_location' => Yii::t('app', 'Sub Location'),
            'village' => Yii::t('app', 'Village / Estate'),
            'occupation' => Yii::t('app', 'Occupation'),
            'employed' => Yii::t('app', 'Formally Employed'),
            'staff_no' => Yii::t('app', 'Staff No.'),
            'employer_name' => Yii::t('app', 'Employer\'s Name'),
            'employer_phone' => Yii::t('app', 'Employer\'s Phone'),
            'employer_email' => Yii::t('app', 'Employer\'s Email'),
            'employer_postal_no' => Yii::t('app', 'Employer\'s Postal No.'),
            'employer_postal_code' => Yii::t('app', 'Employer\'s Postal Code'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\activeQueries\ApplicantsGuarantorsQuery the active query used by this AR class.
     */
    public static function find() {
        return new \frontend\modules\client\modules\student\activeQueries\ApplicantsGuarantorsQuery(get_called_class());
    }

    /**
     * 
     * @param integer $pk guarantor id
     * @return ApplicantsGuarantors model
     */
    public static function returnGuarantor($pk) {
        return static::find()->byPk($pk);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param string $gender gender
     * @param integer $id_no national id. no.
     * @param integer $phone phone number
     * @param string $email email
     * @param string $kra_pin kra pin
     * @return ApplicantsGuarantors models
     */
    public static function searchGuarantors($applicant, $gender, $id_no, $phone, $email, $kra_pin) {
        return static::find()->searchGuarantors($applicant, $gender, $id_no, $phone, $email, $kra_pin);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param integer $applicant_yob applicant year of birth
     * @return ApplicantsGuarantors model
     */
    public static function newGuarantor($applicant, $applicant_yob) {
        $model = new ApplicantsGuarantors;

        $model->applicant = $applicant;

        $model->yob = $applicant_yob - ApplicantsParents::min_age_difference;

        $model->employed = self::employed_no;

        $model->created_by = Yii::$app->user->identity->username;

        return $model;
    }

    /**
     * 
     * @param integer $id guarantor id
     * @param integer $applicant applicant id
     * @param integer $applicant_yob applicant year of birth
     * @return ApplicantsGuarantors model
     */
    public static function guarantorToLoad($id, $applicant, $applicant_yob) {
        return is_object($model = static::returnGuarantor($id)) ? $model : static::newGuarantor($applicant, $applicant_yob);
    }

    /**
     * 
     * @param integer $applicant_id applicant id
     * @return ApplicantsGuarantors models
     */
    public static function guarantorsToLoad($applicant_id) {
        return static::searchGuarantors($applicant_id, null, null, null, null, null);
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
     * @return array employeds
     */
    public static function employeds() {
        return [
            self::employed_no => 'No',
            self::employed_yes => 'Yes',
        ];
    }

    /**
     * 
     * @return integer maximum guarantor's yob
     */
    public static function youngest() {
        return ApplicantsParents::youngest();
    }

    /**
     * 
     * @return integer minimum guarantor's yob
     */
    public static function oldest() {
        return ApplicantsParents::oldest();
    }

}
