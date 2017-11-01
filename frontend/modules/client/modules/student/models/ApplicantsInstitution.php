<?php

namespace frontend\modules\client\modules\student\models;

use Yii;
use common\models\StaticMethods;
use common\models\LmBaseEnums;

/**
 * This is the model class for table "{{%applicants_institution}}".
 *
 * @property integer $id
 * @property integer $applicant
 * @property string $country
 * @property integer $level_of_study
 * @property integer $institution_type
 * @property integer $admission_category
 * @property string $institution_code
 * @property string $institution_branch_code
 * @property string $faculty
 * @property string $department
 * @property string $registration_no
 * @property integer $course_category
 * @property integer $course_type
 * @property string $course_code
 * @property integer $year_of_admission
 * @property integer $admission_month
 * @property double $duration
 * @property integer $year_of_completion
 * @property integer $completion_month
 * @property string $year_of_study
 * @property integer $annual_fees
 * @property integer $annual_upkeep
 * @property integer $amount_can_raise
 * @property integer $amount_applied
 * @property integer $need_bursary
 * @property string $narration
 * @property string $created_by
 * @property string $created_at
 * @property string $modified_by
 * @property string $modified_at
 */
class ApplicantsInstitution extends \yii\db\ActiveRecord {

    const need_bursary_yes = '1';
    const need_bursary_no = '0';
    
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%applicants_institution}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['applicant', 'country', 'level_of_study', 'institution_type', 'admission_category', 'institution_code', 'institution_branch_code', 'registration_no', 'course_category', 'course_type', 'course_code', 'year_of_admission', 'duration', 'year_of_completion', 'year_of_study'], 'required'],
            [['applicant', 'level_of_study', 'institution_type', 'admission_category', 'course_category', 'course_type', 'year_of_admission', 'admission_month', 'year_of_completion', 'completion_month', 'annual_fees', 'annual_upkeep', 'amount_can_raise', 'amount_applied', 'need_bursary'], 'integer'],
            [['country', 'duration', 'year_of_study', 'narration'], 'string'],
            [['institution_code', 'institution_branch_code', 'course_code'], 'string', 'max' => 20],
            [['faculty', 'department'], 'string', 'min' => 10, 'max' => 60],
            [['faculty', 'department', 'registration_no', 'narration'], 'notNumerical'],
            [['faculty', 'department', 'registration_no', 'annual_fees', 'annual_upkeep', 'amount_can_raise', 'amount_applied', 'narration'], 'sanitizeString'],
            [['registration_no'], 'string', 'min' => 7, 'max' => 15],
            [['annual_fees', 'annual_upkeep', 'amount_can_raise', 'amount_applied'], 'string', 'min' => 4, 'max' => 6],
            [['annual_fees', 'annual_upkeep', 'amount_can_raise', 'amount_applied'], 'positiveValue'],
            [['created_at', 'modified_at'], 'safe'],
            [['created_by', 'modified_by'], 'string', 'max' => 15],
            ['amount_applied', 'amountApplied'],
            ['completion_month', 'completionYearAndMonth']
        ];
    }
    
    /**
     * compute completion year and month
     */
    public function completionYearAndMonth() {
        $completion = StaticMethods::monthArithmentics($this->year_of_admission, $this->admission_month, $this->duration, null);
        
        $this->year_of_completion = $completion[0];
        
        $this->completion_month = $completion[1];
    }
    
    /**
     * amount applied must be greater than zero
     */
    public function amountApplied() {
        $this->amount_applied = array_sum([$this->annual_fees, $this->annual_upkeep]) - array_sum([$this->amount_can_raise]);
        
        if ($this->amount_applied <= 0)
            $this->amount_applied = null;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'applicant' => Yii::t('app', 'Applicant'),
            'country' => Yii::t('app', 'Country'),
            'level_of_study' => Yii::t('app', 'Level Of Study'),
            'institution_type' => Yii::t('app', 'Institution Type'),
            'admission_category' => Yii::t('app', 'Admission Category'),
            'institution_code' => Yii::t('app', 'Institution Name'),
            'institution_branch_code' => Yii::t('app', 'Campus Name'),
            'faculty' => Yii::t('app', 'Faculty'),
            'department' => Yii::t('app', 'Department'),
            'registration_no' => Yii::t('app', 'Registration No'),
            'course_category' => Yii::t('app', 'Course Category'),
            'course_type' => Yii::t('app', 'Course Type'),
            'course_code' => Yii::t('app', 'Course Name'),
            'year_of_admission' => Yii::t('app', 'Year Of Admission'),
            'admission_month' => Yii::t('app', 'Admission Month'),
            'duration' => Yii::t('app', 'Duration'),
            'year_of_completion' => Yii::t('app', 'Year Of Completion'),
            'completion_month' => Yii::t('app', 'Completion Month'),
            'year_of_study' => Yii::t('app', 'Year Of Study'),
            'annual_fees' => Yii::t('app', 'Annual Fees'),
            'annual_upkeep' => Yii::t('app', 'Annual Upkeep'),
            'amount_can_raise' => Yii::t('app', 'Amount Can Raise'),
            'amount_applied' => Yii::t('app', 'Amount Applied'),
            'need_bursary' => Yii::t('app', 'Need Bursary'),
            'narration' => Yii::t('app', 'Narration'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_at' => Yii::t('app', 'Modified At')
        ];
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\activeQueries\ApplicantsInstitutionQuery the active query used by this AR class.
     */
    public static function find() {
        return new \frontend\modules\client\modules\student\activeQueries\ApplicantsInstitutionQuery(get_called_class());
    }

    /**
     * 
     * @param integer $pk institution id
     * @return ApplicantsInstitution model
     */
    public static function returnInstitution($pk) {
        return static::find()->byPk($pk);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @return ApplicantsInstitution model
     */
    public static function forApplicant($applicant) {
        return static::find()->forApplicant($applicant);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @return ApplicantsInstitution model
     */
    public static function newInstitution($applicant) {
        $model = new ApplicantsInstitution;

        $model->applicant = $applicant;
        
        $model->country = 'KEN';
        
        $model->year_of_admission = date('Y') - 1;
        
        $model->admission_month = date('m') * 1;
        
        $model->year_of_study = 1;
        
        $model->level_of_study = LmBaseEnums::studyLevel(LmBaseEnums::study_level_degree)->VALUE;
        
        $model->duration = LmBaseEnums::studyDuration($model->level_of_study);
        
        $model->completionYearAndMonth();

        $model->admission_category = LmBaseEnums::admission_category_public_govt_sponsored;
        
        $model->need_bursary = self::need_bursary_no;

        $model->created_by = Yii::$app->user->identity->username;

        return $model;
    }

    /**
     * 
     * @param integer $id institution id
     * @param integer $applicant applicant id
     * @return ApplicantsInstitution model
     */
    public static function institutionToLoad($id, $applicant) {
        return is_object($model = static::returnInstitution($id)) || is_object($model = static::forApplicant($applicant)) ? $model : static::newInstitution($applicant);
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
