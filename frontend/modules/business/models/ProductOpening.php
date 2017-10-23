<?php

namespace frontend\modules\business\models;

use Yii;
use common\models\StaticMethods;
use common\models\LmBaseEnums;

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
                'whenClient' =>  "
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

}
