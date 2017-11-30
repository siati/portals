<?php

namespace frontend\modules\business\models;

use Yii;
use frontend\modules\client\modules\student\models\Applicants;
use frontend\modules\client\modules\student\models\EducationBackground;
use frontend\modules\client\modules\student\models\ApplicantsInstitution;
use frontend\modules\client\modules\student\models\ApplicantsEmployment;
use frontend\modules\client\modules\student\models\ApplicantsGuarantors;
use frontend\modules\client\modules\student\models\FinancialLiteracyScores;
use common\models\LmBaseEnums;
use common\models\StaticMethods;
use common\models\PDFGenerator;
use common\models\Docs;

/**
 * This is the model class for table "{{%applications}}".
 *
 * @property integer $id
 * @property integer $applicant
 * @property integer $application
 * @property string $serial_no
 * @property string $created_by
 * @property string $created_at
 * @property integer $prints
 * @property string $printed_by
 * @property string $printed_at
 * @property string $print_out
 * @property integer $appeal_prints
 * @property string $appeal_printed_by
 * @property string $appeal_printed_at
 * @property string $appeal_print_out
 * @property string $appeal_origin
 */
class Applications extends \yii\db\ActiveRecord {

    const appeal_origin_system = 0;
    const appeal_origin_applicant = 1;
    const print_modified = 'print_modified';
    const print_unmodified = 'print_unmodified';
    const error_incurred = 'error_incurred';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%applications}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['applicant', 'application', 'serial_no', 'created_by'], 'required'],
            [['applicant', 'application', 'prints', 'appeal_prints'], 'integer'],
            [['created_at', 'printed_at', 'appeal_printed_at'], 'safe'],
            [['appeal_origin'], 'string'],
            [['serial_no', 'created_by', 'printed_by', 'appeal_printed_by'], 'string', 'max' => 20],
            [['print_out', 'appeal_print_out'], 'string', 'max' => 128],
            [['serial_no'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'applicant' => Yii::t('app', 'Applicant'),
            'application' => Yii::t('app', 'Application'),
            'serial_no' => Yii::t('app', 'Serial Number'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'prints' => Yii::t('app', 'Number Printed'),
            'printed_by' => Yii::t('app', 'Printed By'),
            'printed_at' => Yii::t('app', 'Printed At'),
            'print_out' => Yii::t('app', 'Form Location'),
            'appeal_prints' => Yii::t('app', 'Number Appeal Printed'),
            'appeal_printed_by' => Yii::t('app', 'Appeal Printed By'),
            'appeal_printed_at' => Yii::t('app', 'Appeal Printed At'),
            'appeal_print_out' => Yii::t('app', 'Appeal Form Location'),
            'appeal_origin' => Yii::t('app', 'Appeal Origin'),
        ];
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\business\activeQueries\ApplicationsQuery the active query used by this AR class.
     */
    public static function find() {
        return new \frontend\modules\business\activeQueries\ApplicationsQuery(get_called_class());
    }

    /**
     * 
     * @param integer $pk application id
     * @return Applications model
     */
    public static function returnApplication($pk) {
        return static::find()->byPk($pk);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param integer $application application id
     * @param string $serial_no serial number
     * @param integer $prints number application printed
     * @param string $printed_at_since time application printed
     * @param string $printed_at_till time application printed
     * @param integer $appeal_prints number application appeal printed
     * @param string $appeal_printed_at_since time application appeal printed
     * @param string $appeal_printed_at_till time application appeal printed
     * @param integer $appeal_origin invoked appeal: 0 - system, 1 - applicant
     * @param string $oneOrAll one or all
     * @return Applications model(s)
     */
    public static function searchApplications($applicant, $application, $serial_no, $prints, $printed_at_since, $printed_at_till, $appeal_prints, $appeal_printed_at_since, $appeal_printed_at_till, $appeal_origin, $oneOrAll) {
        return static::find()->searchApplications($applicant, $application, $serial_no, $prints, $printed_at_since, $printed_at_till, $appeal_prints, $appeal_printed_at_since, $appeal_printed_at_till, $appeal_origin, $oneOrAll);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @return Applications models
     */
    public static function forApplicant($applicant) {
        return static::searchApplications($applicant, null, null, null, null, null, null, null, null, null, self::all);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param integer $application application id
     * @return Applications model
     */
    public static function byApplicantAndApplication($applicant, $application) {
        return static::searchApplications($applicant, $application, null, null, null, null, null, null, null, null, self::one);
    }

    /**
     * 
     * @param string $serial_no serial number
     * @return Applications model
     */
    public static function bySerialNo($serial_no) {
        return static::searchApplications(null, null, $serial_no, null, null, null, null, null, null, null, self::one);
    }

    /**
     * 
     * @param integer $application application id
     * @return integer number of applications
     */
    public static function countApplications($application) {
        return static::find()->countApplications($application);
    }

    /**
     * 
     * @param integer $application application id
     * @return integer|string max serial number for [[$application]]
     */
    public static function maxSerial($application) {
        return static::find()->maxSerial($application);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param integer $application application id
     * @return Applications model
     */
    public static function newApplication($applicant, $application) {
        $model = new Applications;

        $model->applicant = $applicant;
        $model->application = $application;
        $model->appeal_prints = $model->prints = 0;
        $model->appeal_origin = self::appeal_origin_applicant;

        $model->created_by = Yii::$app->user->identity->username;

        return $model;
    }

    /**
     * 
     * @param integer $id application id
     * @param integer $applicant applicant id
     * @param integer $application application id
     * @param string $serial_no serial number
     * @return Applications model
     */
    public static function applicationToLoad($id, $applicant, $application, $serial_no) {
        return is_object($model = static::returnApplication($id)) || (!empty($applicant) && !empty($application) && is_object($model = static::byApplicantAndApplication($applicant, $application))) || (!empty($serial_no) && is_object($model = static::bySerialNo($serial_no))) ? $model : static::newApplication($applicant, $application);
    }

    /**
     * 
     * @param boolean $is_appeal true - application is appeal
     * @return boolean true - model saved
     */
    public function modelSave($is_appeal) {
        if ($this->isNewRecord) {
            $this->created_at = StaticMethods::now();
            $this->applicationSerial();
            $wasNew = true;
        }

        $print = $is_appeal ? $this->printAppealForm() : $this->printApplicationForm();

        if ($print == self::print_modified)
            if ($this->save(false) || (!empty($wasNew) && !$this->hasErrors()))
                ApplicationViews::newView($this->id, $is_appeal ? ApplicationViews::appeal_yes : ApplicationViews::appeal_no, ApplicationViews::new_print_yes);
            else {
                Docs::deleteFile(PDFGenerator::category_laf, $is_appeal ? $this->appeal_print_out : $this->print_out);
                $print = false;
            } else
        if ($print == self::print_unmodified)
            ApplicationViews::newView($this->id, $is_appeal ? ApplicationViews::appeal_yes : ApplicationViews::appeal_no, ApplicationViews::new_print_no);

        return $print;
    }

    /**
     * assign application serial number
     */
    public function applicationSerial() {
        if (is_numeric($count = static::maxSerial($this->application)) && $count > 0)
            $this->serial_no = $count + 1;
        else {
            $application = ProductOpening::returnOpening($this->application);
            $this->serial_no = substr($application->academic_year, 2, 2) . substr($application->academic_year, 7, 2) . $application->subsequent . '000001';
        }
    }

    /**
     * @return array missing application parts requiring attention
     */
    public function compileApplication() {
        foreach (ProductOpeningSettings::forApplicationSettingAndActive($this->application, null, ProductOpeningSettings::active, ProductOpeningSettings::all) as $setting) {
            $setting->setting == ProductSettings::primary && !is_object(EducationBackground::searchEducations($this->applicant, EducationBackground::study_level_primary)) ? $profile[Applicants::profile_has_education_background_primary] = [Applicants::narration => 'Primary Education Details Missing', Applicants::required => true] : '';
            $setting->setting == ProductSettings::secondary && !is_object(EducationBackground::searchEducations($this->applicant, EducationBackground::study_level_secondary)) ? $profile[Applicants::profile_has_education_background_secondary] = [Applicants::narration => 'Secondary Education Details Missing', Applicants::required => true] : '';
            $setting->setting == ProductSettings::certificate && !is_object(EducationBackground::searchEducations($this->applicant, EducationBackground::study_level_certificate)) ? $profile[Applicants::profile_has_education_background_certificate] = [Applicants::narration => 'Tertiary Certificate Missing', Applicants::required => true] : '';
            $setting->setting == ProductSettings::diploma && !is_object(EducationBackground::searchEducations($this->applicant, EducationBackground::study_level_diploma)) ? $profile[Applicants::profile_has_education_background_diploma] = [Applicants::narration => 'Diploma Certificate Missing', Applicants::required => true] : '';
            $setting->setting == ProductSettings::degree && !is_object(EducationBackground::searchEducations($this->applicant, EducationBackground::study_level_degree)) ? $profile[Applicants::profile_has_education_background_degree] = [Applicants::narration => 'Degree Certificate Missing', Applicants::required => true] : '';
            $setting->setting == ProductSettings::masters && !is_object(EducationBackground::searchEducations($this->applicant, EducationBackground::study_level_masters)) ? $profile[Applicants::profile_has_education_background_masters] = [Applicants::narration => 'Masters Certificate Missing', Applicants::required => true] : '';
            $setting->setting == ProductSettings::phd && !is_object(EducationBackground::searchEducations($this->applicant, EducationBackground::study_level_phd)) ? $profile[Applicants::profile_has_education_background_phd] = [Applicants::narration => 'PhD Certificate Missing', Applicants::required => true] : '';
            $setting->setting == ProductSettings::tuition_or_upkeep && is_array($tuition_or_upkeep = ProductOpeningSettings::tuitionOrUpkeep($this->application)) && !empty($tuition_or_upkeep[ProductSettings::no]) && (!is_object($applicant = Applicants::returnApplicant($this->applicant)) || (empty($applicant->account_number) && empty($applicant->smart_card_number))) ? $profile[Applicants::profile_has_bank] = [Applicants::narration => 'Bank Details Missing', Applicants::required => true] : '';
            $setting->setting == ProductSettings::has_society_narration && (!is_object($institution = ApplicantsInstitution::forApplicant($this->applicant)) || empty($institution->narration)) ? $profile[Applicants::profile_has_institution_narration] = [Applicants::narration => 'Society Narration Missing', Applicants::required => true] : '';
            $setting->setting == ProductSettings::employed && ((!is_object($applicant) && !is_object($applicant = Applicants::returnApplicant($this->applicant))) || $applicant->employed != LmBaseEnums::yesOrNo(LmBaseEnums::yes)->VALUE || !is_object(ApplicantsEmployment::forApplicant($this->applicant))) ? $profile[Applicants::profile_has_employment] = [Applicants::narration => 'Employment Details Missing', Applicants::required => true] : '';
            $setting->setting == ProductSettings::guarantors && $setting->value > 0 && ($count = count(ApplicantsGuarantors::forApplicant($this->applicant))) < $setting->value ? $profile[Applicants::profile_has_guarantors] = [Applicants::narration => "$count Guarantors\' Details Provided; $setting->value Required", Applicants::required => true] : '';
            $setting->setting == ProductSettings::has_financial_literacy && !is_object(FinancialLiteracyScores::byApplicantAndAcademicYear($this->applicant, ProductOpening::returnOpening($this->application)->academic_year)) ? $profile[Applicants::application_has_financial_literacy] = [Applicants::narration => "Financial Literacy Status Not Upadated", Applicants::required => true] : '';
        }

        return empty($profile) ? [] : $profile;
    }

    /**
     * 
     * @return boolean|string true - form printout available
     */
    public function printApplicationForm() {
        if ($this->printOutExists())
            return self::print_unmodified;

        if ($filename = ProductOpening::applicationFormPrinter($this)) {

            $this->print_out = $filename;

            $this->prints++;

            $this->printed_by = Yii::$app->user->identity->username;

            $this->printed_at = StaticMethods::now();

            return self::print_modified;
        }

        return false;
    }

    /**
     * 
     * @return boolean true - form printout available
     */
    public function printAppealForm() {
        if ($this->appealPrintOutExists())
            return self::print_unmodified;

        if ($filename = ProductOpening::applicationFormPrinter($this)) {

            $this->appeal_print_out = $filename;

            $this->appeal_prints++;

            $this->appeal_printed_by = Yii::$app->user->identity->username;

            $this->appeal_printed_at = StaticMethods::now();

            return self::print_modified;
        }

        return false;
    }

    /**
     * 
     * @return boolean true - application print out exists
     */
    public function printOutExists() {
        return !empty($this->print_out) && Docs::fileExists(Docs::category_laf, $this->print_out, Docs::location);
    }

    /**
     * 
     * @return boolean true - application appeal print out exists
     */
    public function appealPrintOutExists() {
        return !empty($this->appeal_print_out) && Docs::fileExists(Docs::category_laf, $this->appeal_print_out, Docs::location);
    }

}
