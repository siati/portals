<?php

namespace frontend\modules\client\modules\student\models;

use Yii;
use common\models\StaticMethods;

/**
 * This is the model class for table "{{%financial_literacy_scores}}".
 *
 * @property integer $id
 * @property integer $application
 * @property string $module
 * @property integer $attempts
 * @property integer $score
 * @property integer $passed
 * @property integer $created_by
 * @property string $created_at
 * @property integer $modified_by
 * @property string $modified_at
 */
class FinancialLiteracyScores extends \yii\db\ActiveRecord {

    const passed_yes = 1;
    const passed_no = 0;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%financial_literacy_scores}}';
    }

    /**
     *
     * @var integer applicant id 
     */
    public $applicant;

    /**
     *
     * @var integer product opening id 
     */
    public $opening;

    /**
     *
     * @var integer product id  
     */
    public $product;

    /**
     *
     * @var string academic year 
     */
    public $academic_year;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['application', 'module', 'score', 'created_by'], 'required'],
            [['application', 'attempts', 'score', 'passed'], 'integer'],
            [['module', 'created_by', 'modified_by'], 'string', 'min' => 1, 'max' => 15],
            [['created_at', 'modified_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'applicant' => Yii::t('app', 'Applicant'),
            'academic_year' => Yii::t('app', 'Academic Year'),
            'product' => Yii::t('app', 'Product'),
            'opening' => Yii::t('app', 'Product Opening'),
            'application' => Yii::t('app', 'Application'),
            'module' => Yii::t('app', 'Module'),
            'attempts' => Yii::t('app', 'Attempts'),
            'score' => Yii::t('app', 'Score'),
            'passed' => Yii::t('app', 'Passed'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\activeQueries\FinancialLiteracyScoresQuery the active query used by this AR class.
     */
    public static function find() {
        return new \frontend\modules\client\modules\student\activeQueries\FinancialLiteracyScoresQuery(get_called_class());
    }

    /**
     * 
     * @param integer $pk score id
     * @return FinancialLiteracyScores model
     */
    public static function returnScore($pk) {
        return static::find()->byPk($pk);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param string $academic_year academic year
     * @param integer $application application id
     * @param string $module module
     * @param integer $attempts attempts
     * @param integer $score score as percentage
     * @param string $created_at created at
     * @param string $modified_at modified at
     * @param string $oneOrAll one or all
     * @return FinancialLiteracyScores model(s)
     */
    public static function searchScores($applicant, $academic_year, $application, $module, $attempts, $score, $created_at, $modified_at, $oneOrAll) {
        return static::find()->searchScores($applicant, $academic_year, $application, $module, $attempts, $score, $created_at, $modified_at, $oneOrAll);
    }

    /**
     * 
     * @param integer $applicant applicant
     * @return FinancialLiteracyScores model(s)
     */
    public static function forApplicant($applicant) {
        return static::searchScores($applicant, null, null, null, null, null, null, null, self::all);
    }

    /**
     * 
     * @param string $academic_year academic year
     * @return FinancialLiteracyScores model
     */
    public static function forAcademicYear($academic_year) {
        return static::searchScores(null, $academic_year, null, null, null, null, null, null, self::all);
    }

    /**
     * 
     * @param integer $application application id
     * @return FinancialLiteracyScores model
     */
    public static function byApplication($application) {
        return static::searchScores(null, null, $application, null, null, null, null, null, self::one);
    }

    /**
     * 
     * @param integer $applicant applicant
     * @param string $academic_year academic year
     * @return FinancialLiteracyScores model
     */
    public static function byApplicantAndAcademicYear($applicant, $academic_year) {
        return static::searchScores($applicant, $academic_year, null, null, null, null, null, null, self::one);
    }

    /**
     * 
     * @param integer $application application id
     * @return FinancialLiteracyScores model
     */
    public static function newScore($application) {
        $model = new FinancialLiteracyScores;

        $model->application = $application;

        $model->attempts = 0;

        $model->passed = self::passed_no;

        $model->created_by = Yii::$app->user->identity->username;

        return $model;
    }

    /**
     * 
     * @param integer $id score id
     * @param integer $application application id
     * @param integer $applicant applicant id
     * @param string $academic_year academic year
     * @return FinancialLiteracyScores model
     */
    public static function scoreToLoad($id, $application, $applicant, $academic_year) {
        return is_object($model = static::returnScore($id)) || (!empty($application) && is_object($model = static::byApplication($application))) || (!empty($applicant) && !empty($academic_year) && is_object($model = static::byApplicantAndAcademicYear($applicant, $academic_year))) ? $model : static::newScore($application);
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
