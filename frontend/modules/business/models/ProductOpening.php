<?php

namespace frontend\modules\business\models;

use Yii;
use common\models\StaticMethods;

/**
 * This is the model class for table "{{%product_opening}}".
 *
 * @property integer $id
 * @property integer $product
 * @property string $academic_year
 * @property string $subsequent
 * @property string $since
 * @property string $till
 * @property string $grace
 * @property integer $min_apps
 * @property integer $max_apps
 * @property string $created_by
 * @property string $created_at
 * @property string $modified_by
 * @property string $modified_at
 */
class ProductOpening extends \yii\db\ActiveRecord {

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
            [['product', 'subsequent'], 'integer'],
            [['since', 'till', 'grace', 'created_at', 'modified_at'], 'safe'],
            [['min_apps', 'max_apps'], 'integer'],
            [['product'], 'string', 'max' => 11],
            [['academic_year'], 'string', 'max' => 9],
            [['created_by', 'modified_by'], 'string', 'max' => 20],
        ];
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
            'min_apps' => Yii::t('app', 'Minimum No. of Applications'),
            'max_apps' => Yii::t('app', 'Maximum No. of Applications'),
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
        $model->academic_year = $academic_year;
        $model->subsequent = $subsequent;
        
        $model->grace = $model->till = $model->since = substr(StaticMethods::now(), 0, 10);
        
        $model->created_by = Yii::$app->user->identity->username;
        
        return $model;
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
        return is_object($model = static::returnOpening($id)) || is_object($model = static::byProductAcademicyearAndSubsequent($product, $academic_year, $subsequent)) ? $model : static::newOpening($product, $academic_year, $subsequent);
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
