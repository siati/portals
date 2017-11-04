<?php

namespace frontend\modules\business\models;

use Yii;
use common\models\StaticMethods;

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
    public static function searchApplications($applicant, $application, $serial_no, $prints, $printed_at, $appeal_prints, $appeal_printed_at, $appeal_origin, $oneOrAll) {
        return static::find()->searchApplications($applicant, $application, $serial_no, $prints, $printed_at, $appeal_prints, $appeal_printed_at, $appeal_origin, $oneOrAll);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @return Applications models
     */
    public static function forApplicant($applicant) {
        return static::searchApplications($applicant, null, null, null, null, null, null, null, self::all);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param integer $application application id
     * @return Applications model
     */
    public static function byApplicantAndApplication($applicant, $application) {
        return static::searchApplications($applicant, $application, null, null, null, null, null, null, self::one);
    }

    /**
     * 
     * @param string $serial_no serial number
     * @return Applications model
     */
    public static function bySerialNo($serial_no) {
        return static::searchApplications(null, null, $serial_no, null, null, null, null, null, self::one);
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

    public function modelSave($is_appeal) {
        if ($this->isNewRecord) {
            $this->created_at = StaticMethods::now();
        } else
            $is_appeal ? $this->printAppealForm() : $this->printApplicationForm();
    }

}
