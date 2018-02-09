<?php

namespace frontend\modules\business\models;

use Yii;
use common\models\StaticMethods;
use common\models\LmBaseEnums;
use common\models\PDFGenerator;

/**
 * This is the model class for table "{{%product_opening}}".
 *
 * @property integer $id
 * @property integer $product
 * @property string $academic_year
 * @property integer $subsequent
 * @property string $since
 * @property string $till
 * @property string $grace
 * @property integer $min_apps
 * @property integer $max_apps
 * @property integer $consider_counts
 * @property string $appeal_since
 * @property string $appeal_till
 * @property string $created_by
 * @property string $created_at
 * @property string $modified_by
 * @property string $modified_at
 */
class ProductOpening extends \yii\db\ActiveRecord {

    const consider_counts_no = 0;
    const consider_min_counts = 1;
    const consider_max_counts = 2;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%product_opening}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['product', 'academic_year', 'since', 'till', 'grace', 'created_by'], 'required'],
            [['product', 'subsequent', 'min_apps', 'max_apps', 'consider_counts'], 'integer'],
            [['since', 'till', 'grace', 'appeal_since', 'appeal_till', 'created_at', 'modified_at'], 'safe'],
            [['since', 'till', 'grace', 'appeal_since', 'appeal_till'], 'openingDates'],
            [['min_apps', 'max_apps'], 'required',
                'when' => function () {
                    return $this->consider_counts != self::consider_counts_no;
                },
                'whenClient' => "
                    function (attribute, value) {
                        return $('#productopening-consider_counts').val() !== '" . self::consider_counts_no . "';
                    } 
                "
            ],
            [['min_apps', 'max_apps'], 'sanitizeString'],
            [['product', 'min_apps', 'max_apps'], 'positiveValue'],
            [['min_apps', 'max_apps'], 'applicationNumbers'],
            [['product'], 'string', 'max' => 11],
            [['min_apps', 'max_apps'], 'string', 'min' => 1, 'max' => 7],
            [['academic_year'], 'string', 'max' => 9],
            [['created_by', 'modified_by'], 'string', 'max' => 20],
        ];
    }

    /**
     * validate opening dates
     */
    public function openingDates() {
        StaticMethods::timeEmpty($this->since) ? $this->since = null : '';
        StaticMethods::timeEmpty($this->till) ? (empty($this->since) ? $this->till = null : $this->till = $this->since) : ('');
        StaticMethods::timeEmpty($this->grace) ? (empty($this->till) ? $this->grace = null : $this->grace = $this->till) : ('');
        StaticMethods::timeEmpty($this->appeal_since) ? $this->appeal_since = null : '';
        StaticMethods::timeEmpty($this->appeal_till) ? $this->appeal_till = null : '';

        $this->till < $this->since ? $this->addError('till', 'Closing Date cannot be earlier than Opening Date') : '';
        $this->grace < $this->till ? $this->addError('grace', 'Grace Period Date cannot be earlier than Closing Date') : '';
        $this->consider_counts == self::consider_counts_no && $this->grace != $this->till ? $this->addError('grace', 'Not considering Application Counts, Grace Period Date must equal Closing Date') : '';
        !empty($this->appeal_since) && $this->appeal_since < $this->since ? $this->addError('appeal_since', 'Appeal Since cannot be less than Opening Date') : '';
        $this->appeal_till < $this->appeal_since ? $this->addError('appeal_till', 'Appeal Till cannot be less than Appeal Since') : '';
    }

    /**
     * 
     * @param string $attribute attribute of [[$this]]
     */
    public function applicationNumbers($attribute) {
        $this->min_apps < 1 ? $this->min_apps = null : '';
        $this->max_apps < 1 ? $this->max_apps = null : '';

        is_numeric($this->min_apps) && is_numeric($this->max_apps) && $this->max_apps < $this->min_apps ? $this->addError($attribute, 'Please confirm the Minimum and Maximum Application Numbers') : '';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'product' => Yii::t('app', 'Product'),
            'academic_year' => Yii::t('app', 'Academic Year'),
            'subsequent' => Yii::t('app', 'Is Subsequent'),
            'since' => Yii::t('app', 'Open Since'),
            'till' => Yii::t('app', 'Open Till'),
            'grace' => Yii::t('app', 'Grace Period'),
            'min_apps' => Yii::t('app', 'Min. Applications'),
            'max_apps' => Yii::t('app', 'Max. Applications'),
            'consider_counts' => Yii::t('app', 'Consider Counts'),
            'appeal_since' => Yii::t('app', 'Appeal Since'),
            'appeal_till' => Yii::t('app', 'Appeal Till'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\business\activeQueries\ProductOpeningQuery the active query used by this AR class.
     */
    public static function find() {
        return new \frontend\modules\business\activeQueries\ProductOpeningQuery(get_called_class());
    }

    /**
     * 
     * @param integer $pk opening id
     * @return ProductOpening model
     */
    public static function returnOpening($pk) {
        return static::find()->byPk($pk);
    }

    /**
     * 
     * @param integer $product product id
     * @param string $academic_year academic year
     * @param integer $subsequent subsequent: 0 - false, otherwise true
     * @param string $since opening date
     * @param string $till closing date
     * @param string $grace closing for printing
     * @param integer $min_apps minimum applications accepted
     * @param integer $max_apps maximum applications accepted
     * @param string $oneOrAll one or all
     * @return ProductOpening model(s)
     */
    public static function searchOpening($product, $academic_year, $subsequent, $since, $till, $grace, $min_apps, $max_apps, $oneOrAll) {
        return static::find()->searchOpening($product, $academic_year, $subsequent, $since, $till, $grace, $min_apps, $max_apps, $oneOrAll);
    }

    /**
     * 
     * @param integer $product product id
     * @param string $academic_year academic year
     * @param integer $subsequent subsequent: 0 - false, otherwise true
     * @param string $oneOrAll one or all
     * @return ProductOpening model(s)
     */
    public static function forProductAcademicyearAndSubsequent($product, $academic_year, $subsequent, $oneOrAll) {
        return static::searchOpening($product, $academic_year, $subsequent, null, null, null, null, null, $oneOrAll);
    }

    /**
     * 
     * @param integer $product product id
     * @param string $academic_year academic year
     * @param integer $subsequent subsequent: 0 - false, otherwise true
     * @return ProductOpening model(s)
     */
    public static function byProductAcademicyearAndSubsequent($product, $academic_year, $subsequent) {
        return static::forProductAcademicyearAndSubsequent($product, $academic_year, $subsequent, self::one);
    }

    /**
     * 
     * @param integer $product product id
     * @param string $academic_year academic year
     * @param integer $subsequent subsequent: 0 - false, otherwise true
     * @return ProductOpening model
     */
    public static function newOpening($product, $academic_year, $subsequent) {
        $model = new ProductOpening;

        $model->product = $product;

        $model->academic_year = empty($academic_year) ? static::defaultAcademicYear() : $academic_year;

        $model->subsequent = empty($subsequent) ? static::defaultSubsequent($product, $academic_year) : $subsequent;

        $model->grace = $model->till = $model->since = substr(StaticMethods::now(), 0, 10);

        $model->consider_counts = self::consider_min_counts;

        $model->created_by = Yii::$app->user->identity->username;

        return $model;
    }

    /**
     * 
     * @return string academic year
     */
    public static function defaultAcademicYear() {
        return date('m') < 7 ? ((date('Y') - 1) . '/' . date('Y')) : (date('Y') . '/' . (date('Y') + 1));
    }

    /**
     * 
     * @param integer $product product id
     * @param string $academic_year academic year
     * @return integer subsequent 2, first time 1
     */
    public static function defaultSubsequent($product, $academic_year) {
        return !is_object($opening = static::byProductAcademicyearAndSubsequent($product, $academic_year, $first_time = LmBaseEnums::applicantType(LmBaseEnums::applicant_type_first_time)->VALUE)) || $opening->isNewRecord ? $first_time : LmBaseEnums::applicantType(LmBaseEnums::applicant_type_subsequent)->VALUE;
    }

    /**
     * 
     * @param integer $id opening id
     * @param integer $product product id
     * @param string $academic_year academic year
     * @param integer $subsequent subsequent: 0 - false, otherwise true
     * @return ProductOpening model
     */
    public static function openingToLoad($id, $product, $academic_year, $subsequent) {
        return is_object($model = static::returnOpening($id)) || (!empty($product) && is_object($model = static::byProductAcademicyearAndSubsequent($product, $academic_year = empty($academic_year) ? static::defaultAcademicYear() : $academic_year, empty($subsequent) ? static::defaultSubsequent($product, $academic_year) : $subsequent))) ? $model : static::newOpening($product, $academic_year, $subsequent);
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
     * @return boolean true - minimum application setting considered
     */
    public function minimumConsidered() {
        return $this->consider_counts == self::consider_min_counts && is_numeric($this->min_apps) && $this->min_apps > 0;
    }

    /**
     * 
     * @return boolean true - maximum application setting considered
     */
    public function maximumConsidered() {
        return $this->consider_counts == self::consider_max_counts && is_numeric($this->max_apps) && $this->max_apps > 0;
    }

    /**
     * 
     * @return integer number of applications
     */
    public function countApplications() {
        return Applications::countApplications($this->id);
    }

    /**
     * 
     * @param boolean $is_appeal true = is appeal, otherwise is not appeal
     * @param string $datetime datetime
     * @return boolean true - is open by respective opening and closing dates
     */
    public function openByOpeningDates($is_appeal, $datetime) {
        return $is_appeal ?
                (StaticMethods::timeEmpty($this->appeal_since) || $this->appeal_since <= $datetime) && !StaticMethods::timeEmpty($this->appeal_till) && "$this->appeal_till 23:59:59" >= $datetime :
                (StaticMethods::timeEmpty($this->since) || $this->since <= $datetime) && !StaticMethods::timeEmpty($this->till) && "$this->till 23:59:59" >= $datetime;
    }

    /**
     * 
     * @param string $datetime datetime
     * @return boolean true - is open by respective opening and closing dates
     */
    public function openByGraceDates($datetime) {
        return (StaticMethods::timeEmpty($this->since) || $this->since <= $datetime) && !StaticMethods::timeEmpty($this->till) && !StaticMethods::timeEmpty($this->grace) && $this->grace >= $this->till && "$this->grace 23:59:59" >= $datetime;
    }

    /**
     * 
     * @param string $datetime datetime
     * @return boolean true - closed by minimum numbers
     */
    public function closedByMinimumNumbers($datetime) {
        return ((($dontConsiderMinimum = !$this->minimumConsidered()) || ($count = $this->countApplications()) >= $this->min_apps) && !$this->openByOpeningDates(false, $datetime)) ||
                ($dontConsiderMinimum && (isset($count) ? $count : $this->countApplications()) < $this->min_apps && $this->openByGraceDates($datetime));
    }

    /**
     * 
     * @param string $datetime datetime
     * @return boolean true - opened by maximum numbers
     */
    public function openedByMaximumNumbers($datetime) {
        return (($dontConsiderMaximum = !$this->maximumConsidered()) && ($this->openByOpeningDates(false, $datetime) || $this->openByGraceDates($datetime))) ||
                (!$dontConsiderMaximum && $this->countApplications() < $this->max_apps && !$this->openByGraceDates($datetime));
    }

    /**
     * 
     * @param boolean $is_appeal true - is appeal
     * @param string $datetime datetime
     * @return array rationale against which application is open
     */
    public function applicationIsOpen($is_appeal, $datetime) {
        return [
            self::consider_counts_no => $this->openByOpeningDates($is_appeal, $datetime),
            self::consider_min_counts => $is_appeal ? false : !$this->closedByMinimumNumbers($datetime),
            self::consider_max_counts => $is_appeal ? false : $this->openedByMaximumNumbers($datetime)
        ];
    }

    /**
     * 
     * @param boolean $is_appeal true - is appeal
     * @param string $datetime datetime
     * @return boolean true - application is open
     */
    public function applicationIsOpenByEitherCriterion($is_appeal, $datetime) {
        if (is_array($open = $this->applicationIsOpen($is_appeal, $datetime)))
            return $open[self::consider_counts_no] || $open[self::consider_min_counts] || $open[self::consider_max_counts];

        return false;
    }

    /**
     * 
     * @param integer $applicant applicant
     * @param boolean $is_appeal true - is appeal
     * @return boolean true - applicant can view application
     */
    public function applicantCanViewApplication($applicant, $is_appeal, $datetime) {
        $application = Applications::applicationToLoad(null, $applicant, $this->id, null);

        if ($is_appeal)
            return $application->printed(true) || ($application->printed(false) && $this->applicationIsOpenByEitherCriterion(true, $datetime));
        else
            return $application->printed(false) || ($this->applicationIsOpenByEitherCriterion(false, $datetime) && ProductAccessPropertyItems::applicantCanAccessProduct($this->id, $applicant));
    }

    /**
     * 
     * @param boolean $is_appeal true - is appeal
     * @return array application parts
     */
    public function theParts($is_appeal) {
        return $is_appeal ? (ApplicationPartCheckers::checkerPartsAppeal()) : ($this->subsequent == LmBaseEnums::applicantType(LmBaseEnums::applicant_type_subsequent)->VALUE ? ApplicationPartCheckers::checkerPartsSubsequent() : ApplicationPartCheckers::checkerParts());
    }

    /**
     * 
     * @return array descriptions for various application open reasons
     */
    public static function applicationOpenDescriptions() {
        return [
            self::consider_counts_no => 'Application Window Open',
            self::consider_min_counts => 'Targeting Minimum Application Numbers',
            self::consider_max_counts => 'Awaiting Maximum Application Numbers'
        ];
    }

    /**
     * 
     * @return array academic years
     */
    public static function academicYears() {
        foreach (range(date('Y') + 1, 2016, 1) as $year)
            $academic_years[$academic_year = ($year - 1) . "/$year"] = $academic_year;

        return empty($academic_years) ? [] : $academic_years;
    }

    /**
     * @return array consider counts
     */
    public static function considerCounts() {
        return [self::consider_counts_no => 'No', self::consider_min_counts => 'Minimum', self::consider_max_counts => 'Maximum'];
    }

    /**
     * 
     * @param Applications $application model
     * @param boolean $is_appeal true - is appeal
     * @return boolean true - application form printed and saved
     */
    public static function applicationFormPrinter($application, $is_appeal) {
        try {
            $form = PDFGenerator::go(
                            [
                        PDFGenerator::view => '../pdf/application_form/amateur-form',
                        PDFGenerator::view_params => ['application' => $application, 'is_appeal' => $is_appeal]
                            ], [
                        PDFGenerator::css_file => 'frontend/web/css/pdf/application-form.css',
                        PDFGenerator::water_mark => Yii::$app->homeUrl . '../../common/assets/logos/helb-logo.jpg',
                        PDFGenerator::category => PDFGenerator::category_laf
                            ]
            );

            return $form[PDFGenerator::filename];
        } catch (Exception $ex) {
            return false;
        }
    }

}
