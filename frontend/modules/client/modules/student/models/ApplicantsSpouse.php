<?php

namespace frontend\modules\client\modules\student\models;

use Yii;
use common\models\StaticMethods;
use common\models\LmBaseEnums;
use common\models\User;

/**
 * This is the model class for table "{{%applicants_spouse}}".
 *
 * @property integer $id
 * @property integer $applicant
 * @property integer $relationship
 * @property string $fname
 * @property string $mname
 * @property string $lname
 * @property integer $id_no
 * @property integer $phone
 * @property string $email
 * @property string $kra_pin
 * @property integer $employed
 * @property string $employer_name
 * @property integer $employer_phone
 * @property string $employer_email
 * @property string $employee_no
 * @property string $created_by
 * @property string $created_at
 * @property string $modified_by
 * @property string $modified_at
 */
class ApplicantsSpouse extends \yii\db\ActiveRecord {

    const husband = 0;
    const wife = 1;
    const employed_yes = 1;
    const employed_no = 0;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%applicants_spouse}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['applicant', 'relationship', 'fname', 'id_no', 'created_by'], 'required'],
            [['id_no', 'phone', 'employed', 'employer_phone'], 'integer'],
            [['id_no', 'phone', 'employer_phone'], 'positiveValue'],
            [['mname', 'lname'], 'required',
                'when' => function () {
                    return $this->middleOrLastNameRequired();
                },
                'whenClient' => "
                    function (attribute, value) {
                        $('#applicantsspouse-mname, #applicantsspouse-lname').blur();
                        return ($('#applicantsspouse-mname').val() === null || $('#applicantsspouse-mname').val() === '') && ($('#applicantsspouse-lname').val() === null || $('#applicantsspouse-lname').val() === '');
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
                        $('#applicantsspouse-phone, #applicantsspouse-email').blur();
                        return ($('#applicantsspouse-phone').val() === null || $('#applicantsspouse-phone').val() === '') && ($('#applicantsspouse-email').val() === null || $('#applicantsspouse-email').val() === '');
                    }
                ",
                'message' => 'Spouse\'s Phone No. or Email must be provided'
            ],
            [['employee_no', 'kra_pin', 'employer_name'], 'required',
                'when' => function () {
                    return $this->employed == self::employed_yes;
                },
                'whenClient' => "
                    function (attribute, value) {
                        $('#applicantsspouse-employee_no, #applicantsspouse-kra_pin, #applicantsspouse-employer_name').blur();
                        return $('#applicantsspouse-employed').val() === '" . self::employed_yes . "';
                    }
                "
            ],
            [['employer_phone', 'employer_email'], 'required',
                'when' => function () {
                    return $this->employed == self::employed_yes && (!empty($this->employer_phone) || !empty($this->employer_email));
                },
                'whenClient' => "
                    function (attribute, value) {
                        $('#applicantsspouse-employer_phone, #applicantsspouse-employer_email').blur();
                        return $('#applicantsspouse-employed').val() === '" . self::employed_yes . "' && (($('#applicantsspouse-employer_phone').val() === null || $('#applicantsspouse-employer_phone').val() === '') && ($('#applicantsspouse-employer_email').val() === null || $('#applicantsspouse-employer_email').val() === ''));
                    }
                ",
                'message' => 'Employer\'s Phone No. or Email must be provided'
            ],
            [['id_no', 'kra_pin'], 'notOwnSpouse'],
            [['id_no', 'kra_pin'], 'parentNotBeSpouse'],
            ['id_no', 'notOwnJunior'],
            [['applicant', 'relationship'], 'integer'],
            [['employed'], 'string'],
            [['created_at', 'modified_at'], 'safe'],
            [['fname', 'mname', 'lname', 'created_by', 'modified_by'], 'string', 'min' => 3, 'max' => 20],
            [['fname', 'mname', 'lname', 'employee_no', 'employer_name'], 'notNumerical'],
            [['fname', 'mname', 'lname', 'id_no', 'phone', 'email', 'employee_no', 'kra_pin', 'employer_name', 'employer_phone', 'employer_email'], 'sanitizeString'],
            [['id_no'], 'string', 'min' => 7, 'max' => 8],
            [['phone', 'employer_phone'], 'string', 'min' => 9, 'max' => 13],
            [['phone', 'employer_phone'], 'kenyaPhoneNumber'],
            [['email', 'employer_email'], 'string', 'max' => 40],
            [['email', 'employer_email'], 'email'],
            [['email', 'employer_email'], 'toLowerCase'],
            [['employer_name'], 'string', 'min' => 10, 'max' => 40],
            [['kra_pin'], 'string', 'max' => 11, 'length' => 11],
            [['kra_pin', 'employee_no'], 'toUpperCase'],
            [['kra_pin'], 'KRAPin'],
            [['employee_no'], 'string', 'min' => 3, 'max' => 15],
        ];
    }

    /**
     * 
     * @return boolean true - spouse's middle or last name is required
     */
    public function middleOrLastNameRequired() {
        return empty($this->mname) && empty($this->lname);
    }

    /**
     * applicant should not pose a spouse himself
     * 
     * @param string $attribute attribute of spouse
     */
    public function notOwnSpouse($attribute) {
        if (is_object($user = User::notOwnSenior($attribute, $this->$attribute)) && is_object($applicant = Applicants::returnApplicant($user->id)))
            if ($applicant->id == $this->applicant)
                $this->addError($attribute, 'You\'re trying to be your own spouse!');
            else
            if ($applicant->isMinor())
                $this->addError($attribute, 'Please confirm spouse\'s year of birth!');
            else
            if ((!empty($user->id_no) && !empty($this->id_no) && $user->id_no != $this->id_no) || (!empty($user->kra_pin) && !empty($this->kra_pin) && $user->kra_pin != $this->kra_pin))
                $this->addError($attribute, 'Please confirm your spouse\'s ID. No. or KRA PIN');
    }

    /**
     * spouse cannot be parent
     * 
     * @param string $attribute attribute of [[$this]]
     */
    public function parentNotBeSpouse($attribute) {
        is_object(ApplicantsParents::siblingNotParent($attribute, $this->$attribute, $this->applicant)) ? $this->addError($attribute, 'You\'ve already used this ' . $this->getAttributeLabel($attribute) . ' against your parents') : '';
    }

    /**
     * spouse cannot be sibling too
     * 
     * @param string $attribute attribute of [[$this]]
     */
    public function notOwnJunior($attribute) {
        is_object(ApplicantsSiblingEducationExpenses::notOwnSenior($attribute, $this->$attribute, $this->applicant)) ? $this->addError($attribute, 'You\'ve already used this ' . $this->getAttributeLabel($attribute) . ' against your sibling') : '';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'applicant' => Yii::t('app', 'Applicant'),
            'relationship' => Yii::t('app', 'Relationship'),
            'fname' => Yii::t('app', 'First Name'),
            'mname' => Yii::t('app', 'Middle Name'),
            'lname' => Yii::t('app', 'Last Name'),
            'id_no' => Yii::t('app', 'National ID. No.'),
            'phone' => Yii::t('app', 'Phone No.'),
            'email' => Yii::t('app', 'Email'),
            'kra_pin' => Yii::t('app', 'KRA PIN'),
            'employed' => Yii::t('app', 'Employed'),
            'employer_name' => Yii::t('app', 'Employer\'s Name'),
            'employer_phone' => Yii::t('app', 'Employer\'s Phone'),
            'employer_email' => Yii::t('app', 'Employer\'s Email'),
            'employee_no' => Yii::t('app', 'Employee No.'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\activeQueries\ApplicantsSpouseQuery the active query used by this AR class.
     */
    public static function find() {
        return new \frontend\modules\client\modules\student\activeQueries\ApplicantsSpouseQuery(get_called_class());
    }

    /**
     * 
     * @param integer $pk spouse id
     * @return ApplicantsSpouse model
     */
    public static function returnSpouse($pk) {
        return static::find()->byPk($pk);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param integer $relationship relationship
     * @param integer $id_no national id no
     * @param integer $phone phone no
     * @param string $email email
     * @param string $kra_pin kra pin
     * @param string $oneOrAll one or all
     * @return ApplicantsSpouse model(s)
     */
    public static function searchSpouses($applicant, $relationship, $id_no, $phone, $email, $kra_pin, $oneOrAll) {
        return static::find()->searchSpouses($applicant, $relationship, $id_no, $phone, $email, $kra_pin, $oneOrAll);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @return ApplicantsSpouse model
     */
    public static function forApplicant($applicant) {
        return static::searchSpouses($applicant, null, null, null, null, null, static::one);
    }

    /**
     * 
     * @param string $attribute attribute of User model
     * @param string $value value of [[$attribute]]
     * @param integer $applicant applicant's id
     * @return ApplicantsParents model
     */
    public static function siblingOrParentNotSpouse($attribute, $value, $applicant) {
        return static::find()->siblingOrParentNotSpouse($attribute, $value, $applicant);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @return ApplicantsSpouse model
     */
    public static function newSpouse($applicant) {
        $model = new ApplicantsSpouse;

        $model->applicant = $applicant;

        $model->spouseRelationship();

        $model->created_by = Yii::$app->user->identity->username;

        return $model;
    }

    /**
     * map spouse relation by applicant's gender
     */
    public function spouseRelationship() {
        $this->relationship = LmBaseEnums::byNameAndValue(LmBaseEnums::gender, Applicants::returnApplicant($this->applicant)->gender)->ELEMENT == LmBaseEnums::gender_female ? self::husband : self::wife;
    }

    /**
     * 
     * @param intger $id spouse id
     * @param integer $applicant applicant id
     * @return ApplicantsSpouse model
     */
    public static function spouseToLoad($id, $applicant) {
        is_object($model = static::returnSpouse($id)) || is_object($model = static::forApplicant($applicant)) ? $model->spouseRelationship() : $model = static::newSpouse($applicant);

        return $model;
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
