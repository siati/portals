<?php

namespace frontend\modules\business\models;

use Yii;
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
     * @return integer|string max serial number for $application
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
