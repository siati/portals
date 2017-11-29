<?php

namespace frontend\modules\client\modules\student\models;

use Yii;
use common\models\User;
use common\models\LmBanks;
use common\models\StaticMethods;
use common\models\LmBaseEnums;

/**
 * This is the model class for table "{{%applicants}}".
 *
 * @property integer $id
 * @property string $fname
 * @property string $mname
 * @property string $lname
 * @property string $dob
 * @property string $gender
 * @property integer $disability
 * @property string $other_disability
 * @property integer $married
 * @property integer $parents
 * @property integer $father_death_cert_no
 * @property integer $mother_death_cert_no
 * @property integer $county
 * @property integer $sub_county
 * @property integer $constituency
 * @property integer $ward
 * @property string $location
 * @property string $sub_location
 * @property string $village
 * @property string $postal_no
 * @property integer $postal_code
 * @property integer $bank
 * @property integer $bank_branch
 * @property integer $account_number
 * @property integer $smart_card_number
 * @property integer $employed
 * @property string $modified_by
 * @property string $modified_at
 */
class Applicants extends \yii\db\ActiveRecord {

    const min_age = 16;
    const maturity = 18;
    const disability_other = -1;
    const disability_none = 0;
    const disability_sight = 1;
    const disability_hearing = 2;
    const disability_physical = 3;
    const disability_multiple = 100;
    const married_no = 0;
    const married_yes = 1;
    const parents_not_applicable = -1;
    const parents_both_alive = 0;
    const parents_father_alive = 1;
    const parents_mother_alive = 2;
    const parents_neither_alive = 3;
    const parents_divorced = 4;
    const parents_separated = 5;
    const parents_single = 6;
    const parents_abandoned = 7;
    
    // for compiling applicant's profile
    const narration = 'narration';
    const required = 'required';
    const profile_has_user = 'user';
    const profile_has_residence = 'residence';
    const profile_has_education_background = 'education_background';
    const profile_has_institution = 'institution';
    const profile_has_father = 'father';
    const profile_has_guardian_to_father = 'guardian_to_father';
    const profile_has_mother = 'mother';
    const profile_has_guardian_to_mother = 'guardian_to_mother';
    const profile_has_guardian = 'guardian';
    const profile_has_family_expenses = 'family_expenses';
    const profile_has_sibling_expenses = 'sibling_expenses';
    const profile_has_savings = 'savings';
    const profile_has_employment = 'employment';
    const profile_has_spouse = 'spouse';
    const profile_has_guarantors = 'guarantors';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%applicants}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['fname', 'lname', 'dob', 'gender'], 'required'],
            [['disability', 'married', 'county', 'sub_county', 'constituency', 'ward'], 'required',
                'when' => function () {
                    return !$this->isNewRecord;
                },
                'whenClient' => "
                    function (attribute, value) {
                        $('#applicants-disability, #applicants-married, #applicants-county, #applicants-sub_county, #applicants-constituency, #applicants-ward').blur();
                        return $('#applicants-id').val() * 1 > 0;
                    } 
                "
            ],
            [['other_disability'], 'required',
                'when' => function () {
                    return $this->disability == self::disability_other || $this->disability == self::disability_multiple;
                },
                'whenClient' => "
                    function (attribute, value) {
                        $('#applicants-other_disability').blur();
                        return $('#applicants-disability').val() === '" . self::disability_other . "' || $('#applicants-disability').val() === '" . self::disability_multiple . "';
                    } 
                "
            ],
            [['father_death_cert_no'], 'required',
                'when' => function () {
                    return $this->fathersDeathCertRequired();
                },
                'whenClient' => "
                    function (attribute, value) {
                        $('#applicants-father_death_cert_no').blur();
                        return $('#applicants-parents').val() === '" . self::parents_neither_alive . "' || $('#applicants-parents').val() === '" . self::parents_mother_alive . "';
                    } 
                "
            ],
            [['mother_death_cert_no'], 'required',
                'when' => function () {
                    return $this->mothersDeathCertRequired();
                },
                'whenClient' => "
                    function (attribute, value) {
                        $('#applicants-mother_death_cert_no').blur();
                        return $('#applicants-parents').val() === '" . self::parents_neither_alive . "' || $('#applicants-parents').val() === '" . self::parents_father_alive . "';
                    } 
                "
            ],
            [['dob', 'modified_at'], 'safe'],
            [['disability', 'married', 'parents', 'father_death_cert_no', 'mother_death_cert_no', 'county', 'sub_county', 'constituency', 'ward', 'postal_no', 'postal_code', 'bank', 'bank_branch', 'account_number', 'smart_card_number', 'employed'], 'integer'],
            [['fname', 'mname', 'lname'], 'string', 'min' => 3, 'max' => 20],
            [['location', 'sub_location', 'village'], 'string', 'min' => 3, 'max' => 30],
            [['father_death_cert_no', 'mother_death_cert_no'], 'string', 'min' => 6, 'max' => 8],
            [['postal_no'], 'string', 'max' => 6],
            [['modified_by'], 'string', 'max' => 15],
            [['other_disability'], 'string', 'min' => 4, 'max' => 40],
            ['dob', 'minimumAcceptableAge'],
            [['father_death_cert_no', 'mother_death_cert_no', 'postal_no', 'account_number', 'smart_card_number'], 'positiveValue'],
            [['fname', 'mname', 'lname', 'other_disability', 'location', 'sub_location', 'village'], 'notNumerical'],
            [['fname', 'mname', 'lname', 'other_disability', 'location', 'sub_location', 'village', 'account_number', 'smart_card_number'], 'sanitizeString'],
            [['postal_no', 'postal_code'], 'fullPostalAddress'],
            ['account_number', 'string', 'min' => 6, 'max' => 16],
            ['smart_card_number', 'string', 'length' => 16],
            [['bank', 'bank_branch', 'account_number'], 'required',
                'when' => function () {
                    return !empty($this->bank) || !empty($this->bank_branch) || !empty($this->account_number) || !empty($this->smart_card_number);
                },
                'whenClient' => "
                    function (attribute, value) {
                        $('#applicants-bank, #applicants-bank_branch, #applicants-account_number').blur();
                        return $('#applicants-bank').val() !== '' || $('#applicants-bank').val() === null || $('#applicants-bank_branch').val() !== '' || $('#applicants-bank_branch').val() === null || $('#applicants-account_number').val() !== '' || $('#applicants-account_number').val() === null || $('#applicants-smart_card_number').val() !== '' || $('#applicants-smart_card_number').val() === null;
                    } 
                ",
                'message' => 'This value is required too'
            ],
            ['account_number', 'validateBankNumber']
        ];
    }

    /**
     * verify account numbers
     */
    public function validateBankNumber() {
        if (is_object($bank = LmBanks::searchBanks($this->bank, null, 'one')) && $bank->CHECKDIGITS == LmBanks::check_digits_yes)
            if (strlen($this->account_number) < $bank->MINIMUMACCOUNTDIGITS)
                $this->addError('account_number', $this->getAttributeLabel('account_number') . " must have at least $bank->MINIMUMACCOUNTDIGITS digits");
            else
            if (strlen($this->account_number) > $bank->MAXIMUMACCOUNTDIGITS)
                $this->addError('account_number', $this->getAttributeLabel('account_number') . " must have at most $bank->MAXIMUMACCOUNTDIGITS digits");
    }

    /**
     * set minimum acceptable age
     */
    public function minimumAcceptableAge() {
        if ($this->myAge() < ($age = self::min_age))
            $this->addError('dob', "Minimum age required age is $age years");
    }

    /**
     * 
     * @return integer age of applicant
     */
    public function myAge() {
        return date('Y') - substr($this->dob, 0, 4);
    }

    /**
     * 
     * @return boolean true - applicant is a minor
     */
    public function isMinor() {
        return $this->myAge() < self::maturity;
    }

    /**
     * 
     * @return boolean true - father's death certificate no is required
     */
    public function fathersDeathCertRequired() {
        if (in_array($this->parents, [self::parents_both_alive, self::parents_father_alive]))
            $this->father_death_cert_no = null;

        return in_array($this->parents, [self::parents_neither_alive, self::parents_mother_alive]);
    }

    /**
     * 
     * @return boolean true - mother's death certificate no is required
     */
    public function mothersDeathCertRequired() {
        if (in_array($this->parents, [self::parents_both_alive, self::parents_mother_alive]))
            $this->mother_death_cert_no = null;

        return in_array($this->parents, [self::parents_neither_alive, self::parents_father_alive]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'fname' => Yii::t('app', 'First Name'),
            'mname' => Yii::t('app', 'Middle Name'),
            'lname' => Yii::t('app', 'Last Name'),
            'dob' => Yii::t('app', 'Date of Birth'),
            'gender' => Yii::t('app', 'Gender'),
            'disability' => Yii::t('app', 'Disability'),
            'other_disability' => Yii::t('app', 'Other Disability or Description'),
            'married' => Yii::t('app', 'Married, living with your spouse'),
            'father_death_cert_no' => Yii::t('app', 'Father\'s Death Cert. No.'),
            'mother_death_cert_no' => Yii::t('app', 'Mother\'s Death Cert. No'),
            'parents' => Yii::t('app', 'Parental Status'),
            'county' => Yii::t('app', 'County'),
            'sub_county' => Yii::t('app', 'Sub County'),
            'constituency' => Yii::t('app', 'Constituency'),
            'ward' => Yii::t('app', 'Ward'),
            'location' => Yii::t('app', 'Location'),
            'sub_location' => Yii::t('app', 'Sub Location'),
            'village' => Yii::t('app', 'Village / Estate'),
            'postal_no' => Yii::t('app', 'Postal No'),
            'postal_code' => Yii::t('app', 'Postal Code'),
            'bank' => Yii::t('app', 'Bank'),
            'bank_branch' => Yii::t('app', 'Bank Branch'),
            'account_number' => Yii::t('app', 'Account Number'),
            'smart_card_number' => Yii::t('app', 'HELB Smart Card No.'),
            'employed' => Yii::t('app', 'You\'re Employed'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\activeQueries\ApplicantsQuery the active query used by this AR class.
     */
    public static function find() {
        return new \frontend\modules\client\modules\student\activeQueries\ApplicantsQuery(get_called_class());
    }

    /**
     * 
     * @param integer $pk applicant id
     * @return Applicants model
     */
    public static function returnApplicant($pk) {
        return static::find()->byPk($pk);
    }

    /**
     * 
     * @return Applicants model
     */
    public static function newApplicant() {
        $model = new Applicants;

        $model->dob = (date('Y') - self::min_age) . date('-m-d');

        $model->disability = self::disability_none;

        $model->married = self::married_no;

        $model->parents = self::parents_both_alive;
        
        $model->employed = LmBaseEnums::yesOrNo(LmBaseEnums::no)->VALUE;

        return $model;
    }

    /**
     * 
     * @param integer $id applicant id
     * @return Applicants model
     */
    public static function applicantToLoad($id) {
        return is_object($model = static::returnApplicant($id)) ? $model : static::newApplicant();
    }

    /**
     * 
     * @return boolean true - model saved
     */
    public function modelSave() {
        if (!$this->isNewRecord) {
            Yii::$app->user->isGuest ? '' : $this->modified_by = Yii::$app->user->identity->username;
            $this->modified_at = StaticMethods::now();
        }

        return $this->save();
    }

    /**
     * 
     * @return boolean true - parental status updated
     */
    public function updateParentalStatus() {
        empty($this->father_death_cert_no) ? $this->father_death_cert_no = null : '';

        empty($this->mother_death_cert_no) ? $this->mother_death_cert_no = null : '';

        $this->modified_by = Yii::$app->user->identity->username;

        $this->modified_at = StaticMethods::now();

        return $this->save(true, ['parents', 'father_death_cert_no', 'mother_death_cert_no']);
    }

    /**
     * 
     * @param User $user model
     * @return boolean true - user saved with personal details
     */
    public function theTransaction($user) {
        $user->isNewRecord ? $user->user_type = User::USER_STUDENT : '';

        ($user->isNewRecord || $this->isNewRecord) && Yii::$app->db->transaction === null ? $transaction = Yii::$app->db->beginTransaction() : '';

        try {
            if ($user->modelSave()) {

                $this->isNewRecord ? $this->id = $user->id : '';

                if ($this->modelSave()) {
                    empty($transaction) ? '' : $transaction->commit();
                    return true;
                }
            }
        } catch (Exception $ex) {
            
        }

        empty($transaction) ? '' : $transaction->rollback();
    }

    /**
     * 
     * @return array required parents
     */
    public function myRequiredParents() {
        return static::requiredParents($this->parents);
    }

    /**
     * total income - father, mother, guardian - alive and paying fees
     * 
     * @return integer total annual family income
     */
    public function myTotalAnnualFamilyIncome() {
        $incomes = [];

        foreach (ApplicantsParents::searchParents($this->id, null, null, null, null, null, null, null, null) as $parent)
            if ((($this->parents == self::parents_both_alive && in_array($parent->relationship, [ApplicantsParents::relationship_father, ApplicantsParents::relationship_mother])) || ($this->parents == self::parents_father_alive && in_array($parent->relationship, [ApplicantsParents::relationship_father])) || ($this->parents == self::parents_mother_alive && in_array($parent->relationship, [ApplicantsParents::relationship_mother])) || (in_array($this->parents, [self::parents_neither_alive, self::parents_abandoned]) && $parent->relationship == ApplicantsParents::relationship_guardian)) && $parent->isPayingFees()) {
                $parent->totalAnnualIncome();

                $parent->hasErrors('total_annual_income') ? '' : array_push($incomes, $parent->total_annual_income);
            }

        return array_sum($incomes);
    }

    /**
     * 
     * @return integer total annual family expenditure
     */
    public function myTotalAnnualFamilyExpenditure() {
        $annual_expenses = [];

        foreach (ApplicantsSiblingEducationExpenses::expensesForApplicant($this->id) as $expense)
            array_push($annual_expenses, $expense->annual_fees);

        $monthly_expenses = [];

        foreach (ApplicantsFamilyExpenses::expensesForApplicant($this->id) as $expense)
            array_push($monthly_expenses, $expense->amount);

        return array_sum($annual_expenses) + array_sum($monthly_expenses) * 12;
    }

    /**
     * 
     * @return integer 
     */
    public function myTotalAnnualFamilySaving() {
        return $this->myTotalAnnualFamilyIncome() - $this->myTotalAnnualFamilyExpenditure();
    }

    /**
     * 
     * @param ApplicantsFamilyExpenses $family_expenses models
     * @param ApplicantsSiblingEducationExpenses $sibling_expenses models
     * @param ApplicantsSiblingEducationExpenses $sibling_expense model
     * @return boolean true - expenditures are in excess than income
     */
    public function expenditureExceedsIncome($family_expenses, $sibling_expenses, $sibling_expense) {
        ApplicantsFamilyExpenses::loadMultiple($family_expenses, Yii::$app->request->post());

        $sibling_expense->load(Yii::$app->request->post());

        $totalIncome = static::returnApplicant($sibling_expense->applicant)->myTotalAnnualFamilyIncome();

        $annual_expenses = [];

        foreach ($sibling_expenses as $expense)
            array_push($annual_expenses, $expense->id == $sibling_expense->id ? $sibling_expense->annual_fees : $expense->annual_fees);

        $monthly_expenses = [];

        foreach ($family_expenses as $expense)
            array_push($monthly_expenses, $expense->amount);

        $totalExpenditure = array_sum($annual_expenses) + array_sum($monthly_expenses) * 12 + ($sibling_expense->isNewRecord && is_numeric($sibling_expense->annual_fees) ? $sibling_expense->annual_fees : 0);

        return $totalExpenditure > $totalIncome;
    }

    /**
     * 
     * @param ApplicantsParents $parents models
     * @param ApplicantsParents $select_parent models
     * @return boolean true - incomes are than expenditure
     */
    public function incomeLessThanExpenditure($parents, $select_parent) {
        $totalExpenditure = static::returnApplicant($select_parent->applicant)->myTotalAnnualFamilyExpenditure();

        $select_parent->load(Yii::$app->request->post());

        $incomes = [];

        foreach ($parents as $parent) {

            $parent->id == $select_parent->id ? $parent->attributes = $select_parent->attributes : '';

            if ($parent->isPayingFees()) {
                $parent->totalAnnualIncome();

                $parent->hasErrors('total_annual_income') ? '' : array_push($incomes, $parent->total_annual_income);
            }
        }

        $select_parent->isNewRecord ? $select_parent->totalAnnualIncome() : '';

        $totalIncome = array_sum($incomes) + ($select_parent->isNewRecord && $select_parent->hasErrors('total_annual_income') && $select_parent->isPayingFees() && is_numeric($select_parent->total_annual_income) ? $select_parent->total_annual_income : 0);

        return $totalExpenditure > $totalIncome;
    }
    
    /**
     * 
     * @return array applicant's missing profile bits
     */
    public function compileProfile() {
        is_object(User::returnUser($this->id)) ? '' : $profile[self::profile_has_user] = [self::narration => 'No User Account', self::required => true];
        
        is_object(ApplicantsResidence::forApplicant($this->id)) ? '' : $profile[self::profile_has_residence] = [self::narration => 'Residence Details Missing', self::required => true];
        
        empty(EducationBackground::searchEducations($this->id, null)) ? $profile[self::profile_has_education_background] = [self::narration => 'Education Background Missing', self::required => true] : '';
        
        is_object(ApplicantsInstitution::forApplicant($this->id)) ? '' : $profile[self::profile_has_institution] = [self::narration => 'Institution Details Missing', self::required => true];
        
        if ($this->isMinor() || $this->parents != self::parents_not_applicable) {
            !is_object($father = ApplicantsParents::byApplicantAndRelationship($this->id, ApplicantsParents::relationship_father)) && ($this->parents == self::parents_both_alive || $this->parents == self::parents_father_alive) ? $profile[self::profile_has_father] = [self::narration => 'Father\'s Details Missing', self::required => true] : '';
            is_object($father) && $father->isMinor() && !is_object(ApplicantsParents::byApplicantAndRelationship($this->id, ApplicantsParents::relationship_guardian_to_father)) ? $profile[self::profile_has_guardian_to_father] = [self::narration => 'Father\'s Guardian\'s Details Missing', self::required => true] : '';
            !is_object($mother = ApplicantsParents::byApplicantAndRelationship($this->id, ApplicantsParents::relationship_mother)) && ($this->parents == self::parents_both_alive || $this->parents == self::parents_mother_alive) ? $profile[self::profile_has_mother] = [self::narration => 'Mother\'s Details Missing', self::required => true] : '';
            is_object($mother) && $mother->isMinor() && !is_object(ApplicantsParents::byApplicantAndRelationship($this->id, ApplicantsParents::relationship_guardian_to_mother)) ? $profile[self::profile_has_guardian_to_mother] = [self::narration => 'Mother\'s Guardian\'s Details Missing', self::required => true] : '';
            !is_object($guardian = ApplicantsParents::byApplicantAndRelationship($this->id, ApplicantsParents::relationship_guardian)) && ($this->parents == self::parents_neither_alive || $this->parents == self::parents_abandoned) ? $profile[self::profile_has_guardian] = [self::narration => 'Guardian\'s Details Missing', self::required => true] : '';
            
            ((is_object($father) && $father->paysFees()) || (is_object($mother) && $mother->paysFees()) || (is_object($guardian) && $guardian->paysFees())) && empty(ApplicantsFamilyExpenses::searchExpenses($this->id, null, ApplicantsFamilyExpenses::all)) ? $profile[self::profile_has_family_expenses] = [self::narration => 'Family Expenses Missing', self::required => true] : '';
            empty(ApplicantsSiblingEducationExpenses::expensesForApplicant($this->id)) ? $profile[self::profile_has_sibling_expenses] = [self::narration => 'Siblings\' Details Missing', self::required => false] : '';
            $this->myTotalAnnualFamilySaving() < 0  ? $profile[self::profile_has_savings] = [self::narration => 'Total family expenditure exceeds total family income', self::required => true] : '';
            empty(ApplicantSponsors::sponsorsForApplicant($this->id)) ? $profile[self::profile_has_sponsors] = [self::narration => 'Sponsor\'s Details Missing', self::required => false] : '';
        }
        
        $this->employed == ($yes = LmBaseEnums::yesOrNo(LmBaseEnums::yes)->VALUE) && !is_object(ApplicantsEmployment::forApplicant($this->id)) ? $profile[self::profile_has_employment] = [self::narration => 'Employment Details Missing', self::required => true] : '';
        
        $this->married == $yes && !is_object(ApplicantsSpouse::forApplicant($this->id)) ? $profile[self::profile_has_spouse] = [self::narration => 'Spouse Details Missing', self::required => true] : '';
        
        return empty($profile) ? [] : $profile;
    }

    /**
     * 
     * @return array married - yes, no
     */
    public static function marrieds() {
        return [self::married_no => 'No', self::married_yes => 'Yes'];
    }

    /**
     * @return array parental statuses
     */
    public static function parentalStatuses() {
        return [
            self::parents_both_alive => 'Both Parents Alive',
            self::parents_father_alive => 'Only Father Alive',
            self::parents_mother_alive => 'Only Mother Alive',
            self::parents_neither_alive => 'Both Parents Deceased',
            self::parents_divorced => 'Divorced',
            self::parents_separated => 'Separated',
            self::parents_single => 'Single Unmarried Parent',
            self::parents_abandoned => 'Abandoned',
            self::parents_not_applicable => 'I\'m Mature and Independent'
        ];    }

    /**
     * 
     * @param integer $parentalStatus parental status
     * @return array required parents
     */
    public static function requiredParents($parentalStatus) {
        switch ($parentalStatus) {
            case self::parents_father_alive:
                return [ApplicantsParents::relationship_father];

            case self::parents_mother_alive:
                return [ApplicantsParents::relationship_mother];

            case self::parents_neither_alive || self::parents_abandoned:
                return [ApplicantsParents::relationship_guardian];

            case self::parents_not_applicable:
                return [];

            default:
                return [ApplicantsParents::relationship_father, ApplicantsParents::relationship_mother];
        }
    }

    /**
     * 
     * @return array disabilities
     */
    public static function disabilities() {
        return [
            self::disability_none => 'None',
            self::disability_sight => 'Sight',
            self::disability_hearing => 'Hearing',
            self::disability_physical => 'Physical',
            self::disability_multiple => 'Multiple',
            self::disability_other => 'Other'
        ];
    }

}
