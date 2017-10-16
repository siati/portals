<?php

namespace frontend\modules\client\modules\student\models;

use Yii;
use common\models\StaticMethods;
use common\models\LmBaseEnums;

/**
 * This is the model class for table "{{%applicants_employment}}".
 *
 * @property integer $id
 * @property integer $applicant
 * @property string $employer_name
 * @property integer $employment_terms
 * @property string $employment_date
 * @property integer $employment_period
 * @property string $department
 * @property string $division
 * @property string $section
 * @property integer $county
 * @property string $town
 * @property string $kra_pin
 * @property integer $phone
 * @property string $email
 * @property integer $postal_no
 * @property integer $postal_code
 * @property string $pf_no
 * @property integer $basic_salary
 * @property integer $net_salary
 * @property string $created_by
 * @property string $created_at
 * @property string $modified_by
 * @property string $modified_at
 */
class ApplicantsEmployment extends \yii\db\ActiveRecord {

    const duration_permanent = 0;
    const max_employment_period_years = 65;
    const min_monthuity = 4000;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%applicants_employment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['applicant', 'employer_name', 'employment_terms', 'employment_date', 'employment_period', 'department', 'division', 'section', 'county', 'town', 'pf_no', 'basic_salary', 'net_salary', 'created_by'], 'required'],
            [['applicant', 'employment_terms', 'employment_period', 'county', 'postal_no', 'postal_code', 'basic_salary', 'net_salary'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['employer_name'], 'string', 'max' => 15],
            [['department', 'division', 'section'], 'string', 'min' => 10, 'max' => 30],
            [['town', 'created_by', 'modified_by'], 'string', 'min' => 3, 'max' => 20],
            [['pf_no', 'town', 'department', 'division', 'section'], 'notNumerical'],
            [['pf_no', 'town', 'department', 'division', 'section', 'email', 'basic_salary', 'net_salary'], 'sanitizeString'],
            ['employment_date', 'employmentDate'],
            [['kra_pin'], 'string', 'length' => 11, 'max' => 11],
            [['kra_pin', 'pf_no'], 'toUpperCase'],
            [['kra_pin'], 'KRAPin'],
            [['phone'], 'kenyaPhoneNumber'],
            [['phone'], 'string', 'min' => 9, 'max' => 13],
            [['email'], 'string', 'max' => 45],
            [['email'], 'email'],
            [['email'], 'toLowerCase'],
            [['phone', 'email'], 'required',
                'when' => function () {
                    return empty($this->phone) && empty($this->email);
                },
                'whenClient' => "
                    function (attribute, value) {
                        $('#applicantsemployment-phone, #applicantsemployment-email').blur();
                        return ($('#applicantsemployment-phone').val() === null || $('#applicantsemployment-phone').val() === '') && ($('#applicantsemployment-email').val() === null || $('#applicantsemployment-email').val() === '');
                    }
                ",
                'message' => 'Parent\'s Phone No. or Email must be provided'
            ],
            ['postal_no', 'string', 'min' => 1, 'max' => 6],
            [['postal_no', 'postal_code'], 'fullPostalAddress'],
            [['pf_no'], 'string', 'min' => 5, 'max' => 10],
            [['basic_salary', 'net_salary'], 'positiveValue'],
            [['basic_salary', 'net_salary'], 'oneThirdRule']
        ];
    }

    /**
     * must be employed as at now
     */
    public function employmentDate() {
        if ($this->employment_date > date('Y-m-d'))
            $this->addError('employment_date', 'Employment Date cannot be later than today');
        else
        if ((substr($this->employment_date, 0, 4) + $this->employment_period) . substr($this->employment_date, 4) < ((date('Y') + 1) . date('-m-d')))
            $this->addError('employment_period', 'Please confirm your employment date and period');
    }

    /**
     * 
     * pre-qualify applicant for salaried loan products
     * 
     * @param string $attribute attribute of [[$this]]
     */
    public function oneThirdRule($attribute) {
        $this->net_salary > $this->basic_salary || $this->net_salary < self::min_monthuity || (array_sum([$this->net_salary]) - self::min_monthuity < (array_sum([$this->basic_salary]) / 3)) ?
                        $this->addError($attribute, 'Your net pay cannot sustain the monthly deductions') : '';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'applicant' => Yii::t('app', 'Applicant'),
            'employer_name' => Yii::t('app', 'Employer\'s Name'),
            'employment_terms' => Yii::t('app', 'Employment Terms'),
            'employment_period' => Yii::t('app', 'Employment Period'),
            'department' => Yii::t('app', 'Department'),
            'division' => Yii::t('app', 'Division'),
            'section' => Yii::t('app', 'Section'),
            'county' => Yii::t('app', 'County Posted'),
            'town' => Yii::t('app', 'Town Posted'),
            'kra_pin' => Yii::t('app', 'Employer\'s KRA PIN'),
            'phone' => Yii::t('app', 'Employer\'s Phone No.'),
            'email' => Yii::t('app', 'Employer\'s Email'),
            'postal_no' => Yii::t('app', 'Employer\'s Postal No.'),
            'postal_code' => Yii::t('app', 'Employer\'s Postal Code'),
            'pf_no' => Yii::t('app', 'Employee / Staff / PF No'),
            'basic_salary' => Yii::t('app', 'Basic Pay'),
            'net_salary' => Yii::t('app', 'Net Pay'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\activeQueries\ApplicantsEmploymentQuery the active query used by this AR class.
     */
    public static function find() {
        return new \frontend\modules\client\modules\student\activeQueries\ApplicantsEmploymentQuery(get_called_class());
    }

    /**
     * 
     * @param integer $pk employment id
     * @return ApplicantsEmployment model
     */
    public static function returnEmployment($pk) {
        return static::find()->byPk($pk);
    }

    /**
     * 
     * @param intger $applicant applicant id
     * @param integer $employment_terms employment terms
     * @param intger $county integer
     * @param string $oneOrAll one or all
     * @return ApplicantsEmployment model(s)
     */
    public static function searchEmployment($applicant, $employment_terms, $county, $oneOrAll) {
        return static::find()->searchEmployment($applicant, $employment_terms, $county, $oneOrAll);
    }

    /**
     * 
     * @param intger $applicant applicant id
     * @return ApplicantsEmployment model
     */
    public static function forApplicant($applicant) {
        return static::searchEmployment($applicant, null, null, self::one);
    }

    /**
     * 
     * @param intger $applicant applicant id
     * @return ApplicantsEmployment model
     */
    public static function newEmployment($applicant) {
        $model = new ApplicantsEmployment;

        $model->applicant = $applicant;

        $model->created_by = Yii::$app->user->identity->username;

        return $model;
    }

    /**
     * 
     * @param integer $id employment id
     * @param intger $applicant applicant id
     * @return ApplicantsEmployment model
     */
    public static function employmentToLoad($id, $applicant) {
        return is_object($model = static::returnEmployment($id)) || is_object($model = static::forApplicant($applicant)) ? $model : static::newEmployment($applicant);
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
     * @param integer $terms employment terms
     * @return array duration in years
     */
    public static function employmentPeriod($terms) {
        return is_object($term = LmBaseEnums::byNameAndValue(LmBaseEnums::employement_terms, $terms)) && strtolower($term->ELEMENT) == strtolower(LmBaseEnums::employement_terms_permanent) ?
                [
            self::max_employment_period_years => 'Permanet'
                ] : [
            1 => 'One Year',
            2 => 'Two Years',
            3 => 'Three Years',
            4 => 'Four Years',
            5 => 'Five Years',
            6 => 'Six Years',
            7 => 'Seven Years',
            8 => 'Eight Years',
            9 => 'Nine Years',
            10 => 'Ten Years',
            11 => 'Eleven Year',
            12 => 'Twelve Years',
            13 => 'Thirteen Years',
            14 => 'Fourteen Years',
            15 => 'Fifteen Years',
            16 => 'Sixteen Years',
            17 => 'Seventeen Years',
            18 => 'Eighteen Years',
            19 => 'Nineteen Years',
            20 => 'Twenty Years',
                ]
        ;
    }

}
