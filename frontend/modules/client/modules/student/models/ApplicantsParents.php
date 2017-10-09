<?php

namespace frontend\modules\client\modules\student\models;

use Yii;
use common\models\User;
use frontend\modules\client\modules\student\models\ApplicantsSiblingEducationExpenses;
use common\models\StaticMethods;
use common\models\LmBaseEnums;

/**
 * This is the model class for table "{{%applicants_parents}}".
 *
 * @property integer $id
 * @property integer $applicant
 * @property integer $relationship
 * @property string $fname
 * @property string $mname
 * @property string $lname
 * @property integer $yob
 * @property string $gender
 * @property integer $birth_cert_no
 * @property integer $id_no
 * @property integer $phone
 * @property string $email
 * @property integer $postal_no
 * @property integer $postal_code
 * @property string $kra_pin
 * @property integer $education_level
 * @property integer $pays_fees
 * @property integer $is_minor
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
 * @property integer $gross_monthly_salary
 * @property integer $farming_annual
 * @property integer $monthly_pension
 * @property integer $business_annual
 * @property integer $govt_support_annual
 * @property integer $relief_annual
 * @property integer $other_annual
 * @property string $created_by
 * @property string $created_at
 * @property string $modified_by
 * @property string $modified_at
 */
class ApplicantsParents extends \yii\db\ActiveRecord {

    const min_age_difference = 9;
    const oldest = 90;
    const relationship_father = 1;
    const relationship_mother = 2;
    const relationship_guardian = 3;
    const relationship_guardian_to_father = 4;
    const relationship_guardian_to_mother = 5;
    const pays_fees_yes = 1;
    const pays_fees_no = 0;
    const is_minor_no = 0;
    const is_minor_yes = 1;
    const employed_no = 0;
    const employed_yes = 1;
    const minimum_annual_income = 3000;

    /**
     *
     * @var integer
     */
    public $total_annual_income;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%applicants_parents}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['applicant', 'relationship', 'fname', 'yob', 'gender', 'county', 'sub_county', 'constituency', 'ward'], 'required'],
            [['relationship', 'applicant', 'yob', 'birth_cert_no', 'id_no', 'phone', 'postal_no', 'postal_code', 'county', 'sub_county', 'constituency', 'ward', 'education_level', 'pays_fees', 'is_minor', 'employed', 'employer_postal_no', 'employer_postal_code', 'gross_monthly_salary', 'farming_annual', 'monthly_pension', 'business_annual', 'govt_support_annual', 'relief_annual', 'other_annual'], 'integer'],
            [['mname', 'lname'], 'required',
                'when' => function () {
                    return $this->middleOrLastNameRequired();
                },
                'whenClient' => "
                    function (attribute, value) {
                        $('#applicantsparents-mname, #applicantsparents-lname').blur();
                        return ($('#applicantsparents-mname').val() === null || $('#applicantsparents-mname').val() === '') && ($('#applicantsparents-lname').val() === null || $('#applicantsparents-lname').val() === '');
                    } 
                ",
                'message' => 'Middle or last name must be provided'
            ],
            [['birth_cert_no', 'id_no'], 'required',
                'when' => function () {
                    return empty($this->birth_cert_no) && empty($this->id_no);
                },
                'whenClient' => "
                    function (attribute, value) {
                        $('#applicantsparents-birth_cert_no, #applicantsparents-id_no').blur();
                        return ($('#applicantsparents-id_no').val() === null || $('#applicantsparents-id_no').val() === '') && ($('#applicantsparents-birth_cert_no').val() === null || $('#applicantsparents-birth_cert_no').val() === '');
                    }
                ",
                'message' => 'Birth Cetificate or National ID. No. must be provided'
            ],
            [['birth_cert_no'], 'required',
                'when' => function () {
                    return $this->birtCertNoIsRequired();
                },
                'whenClient' => "
                    function (attribute, value) {
                        $('#applicantsparents-birth_cert_no').blur();
                        return $('#applicantsparents-yob').val() === null || $('#applicantsparents-yob').val() === '' || (new Date).getFullYear() - $('#applicantsparents-yob').val() * 1 < " . Applicants::maturity . ";
                    }
                "
            ],
            [['id_no'], 'required',
                'when' => function () {
                    return $this->IDNoIsRequired();
                },
                'whenClient' => "
                    function (attribute, value) {
                        $('#applicantsparents-id_no').blur();
                        return $('#applicantsparents-relationship').val() === '" . self::relationship_guardian . "' || $('#applicantsparents-relationship').val() === '" . self::relationship_guardian_to_father . "' || $('#applicantsparents-relationship').val() === '" . self::relationship_guardian_to_mother . "' || (new Date).getFullYear() - $('#applicantsparents-yob').val() * 1 >= " . Applicants::maturity . ";
                    }
                "
            ],
            [['phone', 'email'], 'required',
                'when' => function () {
                    return empty($this->phone) && empty($this->email);
                },
                'whenClient' => "
                    function (attribute, value) {
                        $('#applicantsparents-phone, #applicantsparents-email').blur();
                        return ($('#applicantsparents-phone').val() === null || $('#applicantsparents-phone').val() === '') && ($('#applicantsparents-email').val() === null || $('#applicantsparents-email').val() === '');
                    }
                ",
                'message' => 'Parent\'s Phone No. or Email must be provided'
            ],
            [['occupation', 'total_annual_income'], 'required',
                'when' => function () {
                    return $this->isPayingFees();
                },
                'whenClient' => "
                    function (attribute, value) {
                        $('#applicantsparents-occupation, #applicantsparents-total_annual_income').blur();
                        return $('#applicantsparents-pays_fees').val() === '" . self::pays_fees_yes . "';
                    } 
                "
            ],
            ['occupation', 'required',
                'when' => function () {
                    return is_object($this->isGuarantor());
                },
                'message' => 'Occupation is required, this parent being your guarantor too'
            ],
            [['kra_pin', 'staff_no', 'employer_name', 'gross_monthly_salary'], 'required',
                'when' => function () {
                    return $this->isPayingFees() && $this->isFormallyEmployed();
                },
                'whenClient' => "
                    function (attribute, value) {
                        $('#applicantsparents-kra_pin, #applicantsparents-staff_no, #applicantsparents-employer_name, #applicantsparents-gross_monthly_salary').blur();
                        return $('#applicantsparents-pays_fees').val() === '" . self::pays_fees_yes . "' && $('#applicantsparents-employed').val() === '" . self::employed_yes . "';
                    } 
                "
            ],
            [['employer_phone', 'employer_email'], 'required',
                'when' => function () {
                    return $this->isPayingFees() && $this->isFormallyEmployed() && empty($this->employer_phone) && empty($this->employer_email);
                },
                'whenClient' => "
                    function (attribute, value) {
                        $('#applicantsparents-employer_phone, #applicantsparents-employer_email').blur();
                        return $('#applicantsparents-pays_fees').val() === '" . self::pays_fees_yes . "' && $('#applicantsparents-employed').val() === '" . self::employed_yes . "' && ($('#applicantsparents-employer_phone').val() === null || $('#applicantsparents-employer_phone').val() === '') && ($('#applicantsparents-employer_email').val() === null || $('#applicantsparents-employer_email').val() === '');
                    }
                ",
                'message' => 'Employer\'s Phone No. or Email must be provided'
            ],
            [['birth_cert_no', 'id_no'], 'notOwnJunior'],
            [['id_no', 'kra_pin'], 'notJuniorsSpouse'],
            [['birth_cert_no', 'id_no', 'kra_pin'], 'notOwnParent'],
            [['birth_cert_no', 'id_no', 'kra_pin'], 'distinctDetails'],
            [['fname', 'mname', 'lname', 'location', 'sub_location', 'village', 'occupation', 'employer_name'], 'notNumerical'],
            ['is_minor', 'isMinor'],
            [['fname', 'mname', 'lname'], 'string', 'min' => 3, 'max' => 20],
            [['birth_cert_no'], 'string', 'min' => 6, 'max' => 8],
            [['id_no'], 'string', 'min' => 7, 'max' => 8],
            [['phone', 'employer_phone'], 'string', 'min' => 9, 'max' => 13],
            [['phone', 'employer_phone'], 'kenyaPhoneNumber'],
            [['email', 'employer_email'], 'string', 'max' => 40],
            [['email', 'employer_email'], 'email'],
            [['email', 'employer_email'], 'toLowerCase'],
            [['postal_no', 'employer_postal_no', 'gross_monthly_salary', 'monthly_pension'], 'string', 'max' => 6],
            [['farming_annual', 'business_annual', 'govt_support_annual', 'relief_annual', 'other_annual'], 'string', 'max' => 7],
            [['farming_annual', 'business_annual', 'govt_support_annual', 'relief_annual', 'other_annual', 'gross_monthly_salary', 'monthly_pension'], 'incomeLessThanExpenditure'],
            [['birth_cert_no', 'id_no', 'phone', 'postal_no', 'employer_postal_no', 'employer_phone', 'gross_monthly_salary', 'monthly_pension', 'farming_annual', 'business_annual', 'govt_support_annual', 'relief_annual', 'other_annual'], 'positiveValue'],
            ['total_annual_income', 'totalAnnualIncome'],
            [['postal_no', 'postal_code'], 'fullPostalAddress'],
            [['employer_postal_no', 'employer_postal_code'], 'fullEmployerPostalAddress'],
            [['kra_pin'], 'string', 'max' => 11, 'length' => 11],
            ['kra_pin', 'toUpperCase'],
            [['kra_pin'], 'KRAPin'],
            [['location', 'sub_location', 'village', 'occupation'], 'string', 'min' => 2, 'max' => 30],
            [['staff_no'], 'string', 'min' => 3, 'max' => 10],
            [['employer_name'], 'string', 'min' => 4, 'max' => 45],
            [['fname', 'mname', 'lname', 'birth_cert_no', 'phone', 'email', 'location', 'sub_location', 'village', 'occupation', 'staff_no', 'employer_name', 'employer_phone', 'employer_email'], 'sanitizeString'],
            [['created_at', 'modified_at'], 'safe'],
            [['created_by', 'modified_by'], 'string', 'max' => 15]
        ];
    }

    /**
     * 
     * @return boolean true - id no is required
     */
    public function birtCertNoIsRequired() {
        return date('Y') - $this->yob < Applicants::maturity;
    }

    /**
     * 
     * @return boolean true - parent is minor
     */
    public function isMinor() {
        $this->is_minor = date('Y') - $this->yob < Applicants::maturity ? self::is_minor_yes : self::is_minor_no;

        return $this->is_minor === self::is_minor_yes;
    }

    /**
     * 
     * @return boolean true - id no is required
     */
    public function IDNoIsRequired() {
        return in_array($this->relationship, [self::relationship_guardian, self::relationship_guardian_to_father, self::relationship_guardian_to_mother]) || date('Y') - $this->yob >= Applicants::maturity;
    }

    /**
     * 
     * @return boolean true - parent's middle or last name is required
     */
    public function middleOrLastNameRequired() {
        return empty($this->mname) && empty($this->lname);
    }

    /**
     * 
     * @return boolean true - is paying fees
     */
    public function isPayingFees() {
        return $this->pays_fees == self::pays_fees_yes;
    }

    /**
     * 
     * @return boolean true - is formally employed
     */
    public function isFormallyEmployed() {
        return $this->employed == self::employed_yes;
    }

    /**
     * compute total annual income
     */
    public function totalAnnualIncome() {
        $this->total_annual_income = array_sum([$this->farming_annual, $this->business_annual, $this->govt_support_annual, $this->relief_annual, $this->other_annual, array_sum([$this->gross_monthly_salary, $this->monthly_pension]) * 12]);

        if ($this->isPayingFees() && $this->total_annual_income < self::minimum_annual_income)
            $this->addError('total_annual_income', 'Total Annual Income must be at least KShs. ' . self::minimum_annual_income);
    }

    /**
     * total income must not be less than total expenditure
     * 
     * @param string $attribute attribute of parent
     */
    public function incomeLessThanExpenditure($attribute) {
        Applicants::incomeLessThanExpenditure(ApplicantsParents::searchParents($this->applicant, null, null, null, null, null, null, null, null), static::parentToLoad($this->id, $this->applicant, $this->relationship, null)) ? $this->addError($attribute, 'Total income must not be less than total expenditure') : '';
    }

    /**
     * applicant should not pose a parent himself
     * 
     * @param string $attribute attribute of parent
     */
    public function notOwnParent($attribute) {
        if (is_object($user = User::notOwnSenior($attribute, $this->$attribute)) && is_object($applicant = Applicants::returnApplicant($user->id)))
            if ($applicant->id == $this->applicant)
                $this->addError($attribute, 'You\'re trying to be your own parent / guardian!');
            else
            if ($applicant->isMinor() == $this->IDNoIsRequired())
                $this->addError($attribute, 'Please confirm parent\'s year of birth!');
            else
            if ((!empty($user->id_no) && !empty($this->id_no) && $user->id_no != $this->id_no) || (!empty($user->birth_cert_no) && !empty($this->birth_cert_no) && $user->birth_cert_no != $this->birth_cert_no) || (!empty($user->kra_pin) && !empty($this->kra_pin) && $user->kra_pin != $this->kra_pin))
                $this->addError($attribute, 'Please confirm your parent\'s ID. No. or Birth Cert. No. or KRA PIN');
    }

    /**
     * parent cannot be sibling too
     * 
     * @param string $attribute attribute of [[$this]]
     */
    public function notOwnJunior($attribute) {
        is_object(ApplicantsSiblingEducationExpenses::notOwnSenior($attribute, $this->$attribute, $this->applicant)) ? $this->addError($attribute, 'You\'ve already used this ' . $this->getAttributeLabel($attribute) . ' against your sibling') : '';
    }
    
    /**
     * 
     * parent cannot be spouse too
     * 
     * @param string $attribute attribute of [[$this]]
     */
    public function notJuniorsSpouse($attribute) {
        is_object(ApplicantsSpouse::siblingOrParentNotSpouse($attribute, $this->$attribute, $this->applicant)) ? $this->addError($attribute, 'You\'ve already used this ' . $this->getAttributeLabel($attribute) . ' against your spouse') : '';
    }

    /**
     * applicant cannot reuse certain details
     * 
     * @param string $attribute attribute of parent
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
            'relationship' => Yii::t('app', 'Relationship'),
            'fname' => Yii::t('app', 'First Name'),
            'mname' => Yii::t('app', 'Middle Name'),
            'lname' => Yii::t('app', 'Last Name'),
            'yob' => Yii::t('app', 'Year of Birth'),
            'gender' => Yii::t('app', 'Gender'),
            'birth_cert_no' => Yii::t('app', 'Birth Certificate No.'),
            'id_no' => Yii::t('app', 'National ID. No.'),
            'phone' => Yii::t('app', 'Phone No.'),
            'email' => Yii::t('app', 'Email'),
            'postal_no' => Yii::t('app', 'Postal No'),
            'postal_code' => Yii::t('app', 'Postal Code'),
            'kra_pin' => Yii::t('app', 'KRA PIN'),
            'education_level' => Yii::t('app', 'Highest Education Level'),
            'pays_fees' => Yii::t('app', 'Pays Fees'),
            'is_minor' => Yii::t('app', 'Parent Is Minor'),
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
            'employer_phone' => Yii::t('app', 'Employer\'s Phone No.'),
            'employer_email' => Yii::t('app', 'Employer\'s Email Address'),
            'employer_postal_no' => Yii::t('app', 'Employer\'s Postal No.'),
            'employer_postal_code' => Yii::t('app', 'Employer\'s Postal Code'),
            'gross_monthly_salary' => Yii::t('app', 'Gross Monthly Salary'),
            'farming_annual' => Yii::t('app', 'Annual Farming Income'),
            'monthly_pension' => Yii::t('app', 'Monthly Pension'),
            'business_annual' => Yii::t('app', 'Annual Business Income'),
            'govt_support_annual' => Yii::t('app', 'Annual Govt Support'),
            'relief_annual' => Yii::t('app', 'Annual Relief'),
            'other_annual' => Yii::t('app', 'Other Annual Income'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_at' => Yii::t('app', 'Modified At')
        ];
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\activeQueries\ApplicantsParentsQuery the active query used by this AR class.
     */
    public static function find() {
        return new \frontend\modules\client\modules\student\activeQueries\ApplicantsParentsQuery(get_called_class());
    }

    /**
     * 
     * @param integer $pk parent id
     * @return ApplicantsParents model
     */
    public static function returnParent($pk) {
        return static::find()->byPk($pk);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param integer $relationship relationship
     * @param string $gender gender
     * @param integer $birth_cert_no birth cert. no.
     * @param integer $id_no national id. no.
     * @param integer $phone phone number
     * @param string $email email
     * @param string $kra_pin kra pin
     * @param integer $is_minor is a minor
     * @return ApplicantsParents models
     */
    public static function searchParents($applicant, $relationship, $gender, $birth_cert_no, $id_no, $phone, $email, $kra_pin, $is_minor) {
        return static::find()->searchParents($applicant, $relationship, $gender, $birth_cert_no, $id_no, $phone, $email, $kra_pin, $is_minor);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param integer $relationship relationship
     * @return ApplicantsParents model
     */
    public static function byApplicantAndRelationship($applicant, $relationship) {
        return static::find()->byApplicantAndRelationship($applicant, $relationship);
    }

    /**
     * 
     * @param string $attribute attribute of User model
     * @param string $value value of [[$attribute]]
     * @param integer $applicant applicant's id
     * @return ApplicantsParents model
     */
    public static function siblingNotParent($attribute, $value, $applicant) {
        return static::find()->siblingNotParent($attribute, $value, $applicant);
    }

    /**
     * 
     * @return ApplicantsParents model
     */
    public function myGuardian() {
        return static::byApplicantAndRelationship($this->applicant, static::parentGuardian($this->relationship));
    }

    /**
     * 
     * @return ApplicantsParents model
     */
    public function imGuardianTo() {
        return static::byApplicantAndRelationship($this->applicant, static::guardianParent($this->relationship));
    }

    /**
     * 
     * @return boolean true - can pay fees
     */
    public function canPayFees() {
        return in_array($this->relationship, [self::relationship_father, self::relationship_mother, self::relationship_guardian]);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param integer $id_no parent id
     * @return ApplicantsGuarantors model
     */
    public static function parentIsGuarantor($applicant, $id_no) {
        return !empty($id_no) && count($guarantor = ApplicantsGuarantors::searchGuarantors($applicant, null, $id_no, null, null, null)) > 0 ? $guarantor[0] : false;
    }

    /**
     * 
     * @return ApplicantsGuarantors model
     */
    public function isGuarantor() {
        return static::parentIsGuarantor($this->applicant, $this->id_no);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param integer $relationship relationship
     * @param integer $applicant_yob applicant year of birth
     * @return ApplicantsParents model
     */
    public static function newParent($applicant, $relationship, $applicant_yob) {
        $model = new ApplicantsParents;

        $model->applicant = $applicant;

        $model->relationship = $relationship;

        $relationship == self::relationship_father ? ($model->gender = LmBaseEnums::gender(LmBaseEnums::gender_male)->VALUE) : ($relationship == self::relationship_mother ? $model->gender = LmBaseEnums::gender(LmBaseEnums::gender_female)->VALUE : '');

        $model->yob = $applicant_yob - self::min_age_difference;

        $model->education_level = LmBaseEnums::studyLevel(LmBaseEnums::study_level_none)->VALUE;

        $model->pays_fees = $model->canPayFees() ? self::pays_fees_yes : self::pays_fees_no;

        $model->isMinor();

        $model->employed = self::employed_no;

        $model->created_by = Yii::$app->user->identity->username;

        return $model;
    }

    /**
     * 
     * @param integer $id parent id
     * @param integer $applicant applicant id
     * @param integer $relationship relationship
     * @param integer $applicant_yob applicant year of birth
     * @return ApplicantsParents model
     */
    public static function parentToLoad($id, $applicant, $relationship, $applicant_yob) {
        return is_object($model = static::returnParent($id)) || is_object($model = static::byApplicantAndRelationship($applicant, $relationship)) ? $model : static::newParent($applicant, $relationship, $applicant_yob);
    }

    /**
     * 
     * @param integer $applicant_id applicant id
     * @return array required parents
     */
    public static function parentsToLoad($applicant_id) {
        if (is_object($applicant = Applicants::returnApplicant($applicant_id)))
            foreach ($applicant->myRequiredParents() as $relationship) {
                $relationships[] = $relationship;

                if (static::parentToLoad(null, $applicant->id, $relationship, substr($applicant->dob, 0, 4))->isMinor())
                    ($guardian = static::parentGuardian($relationship)) != $relationship ? $relationships[] = static::parentGuardian($relationship) : '';
            }

        return empty($relationships) ? [] : $relationships;
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

        Yii::$app->db->transaction === null ? $transaction = Yii::$app->db->beginTransaction() : '';

        try {
            if ($this->save() && (!is_object($guarantor = $this->isGuarantor()) || ($guarantor->IDNoIsParents() && $guarantor->save(false)))) {
                empty($transaction) ? '' : $transaction->commit();
                return true;
            }
        } catch (Exception $ex) {
            
        }

        empty($transaction) ? '' : $transaction->rollBack();
    }

    /**
     * 
     * @return array relationships
     */
    public static function relationships() {
        return [
            self::relationship_father => 'Father',
            self::relationship_mother => 'Mother',
            self::relationship_guardian => 'Guardian',
            self::relationship_guardian_to_father => 'Paternal Guardian',
            self::relationship_guardian_to_mother => 'Maternal Guardian',
        ];
    }

    /**
     * 
     * @param integer $relationship relationship
     * @return type string required guardian to parent
     */
    public static function parentGuardian($relationship) {
        switch ($relationship) {
            case self::relationship_father:
                return self::relationship_guardian_to_father;

            case self::relationship_mother:
                return self::relationship_guardian_to_mother;

            default:
                return $relationship;
        }
    }

    /**
     * 
     * @param integer $relationship relationship
     * @return type string required parent for guardian
     */
    public static function guardianParent($relationship) {
        switch ($relationship) {
            case self::relationship_guardian_to_father:
                return self::relationship_father;

            case self::relationship_guardian_to_mother:
                return self::relationship_mother;

            default:
                return $relationship;
        }
    }

    /**
     * 
     * @return array pays fees
     */
    public static function paysFees() {
        return [
            self::pays_fees_yes => 'Yes',
            self::pays_fees_no => 'No'
        ];
    }

    /**
     * 
     * @return array minors
     */
    public static function minors() {
        return [
            self::is_minor_no => 'No',
            self::is_minor_yes => 'Yes',
        ];
    }

    /**
     * 
     * @return integer maximum parents yob
     */
    public static function youngest() {
        return date('Y') - Applicants::min_age - self::min_age_difference;
    }

    /**
     * 
     * @return integer minimum parents yob
     */
    public static function oldest() {
        return date('Y') - self::oldest;
    }

}
