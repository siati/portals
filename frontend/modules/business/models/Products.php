<?php

namespace frontend\modules\business\models;

use Yii;
use common\models\StaticMethods;

/**
 * This is the model class for table "{{%products}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $helb_code
 * @property string $description
 * @property integer $active
 * @property string $logo_owner
 * @property string $logo_partner
 * @property string $watermark
 * @property string $logo_header
 * @property string $created_by
 * @property string $created_at
 * @property string $modified_by
 * @property string $modified_at
 */
class Products extends \yii\db\ActiveRecord {

    const active_yes = '1';
    const active_no = '0';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%products}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['code', 'name', 'active', 'created_by'], 'required'],
            [['created_at', 'modified_at'], 'safe'],
            [['code'], 'string', 'min' => 2, 'max' => 3],
            [['name'], 'string', 'min' => 10, 'max' => 60],
            [['description'], 'string', 'min' => 20, 'max' => 250],
            [['helb_code'], 'string', 'max' => 15],
            [['logo_owner', 'logo_partner', 'watermark', 'logo_header'], 'file', 'extensions' => implode(',', static::extensions()), 'checkExtensionByMimeType' => false],
            [['created_by', 'modified_by'], 'string', 'min' => 3, 'max' => 20],
            [['name', 'code', 'description'], 'sanitizeString'],
            [['name', 'code', 'description'], 'notNumerical'],
            [['code'], 'toUpperCase'],
            [['name', 'code'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Product Code'),
            'name' => Yii::t('app', 'Product Name'),
            'helb_code' => Yii::t('app', 'HELB Code'),
            'description' => Yii::t('app', 'Description'),
            'active' => Yii::t('app', 'Active'),
            'logo_owner' => Yii::t('app', 'Owner\'s Logo'),
            'logo_partner' => Yii::t('app', 'Partner\'s Logo'),
            'watermark' => Yii::t('app', 'Watermark'),
            'logo_header' => Yii::t('app', 'Header Section Logo'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\business\activeQueries\ProductsQuery the active query used by this AR class.
     */
    public static function find() {
        return new \frontend\modules\business\activeQueries\ProductsQuery(get_called_class());
    }

    /**
     * 
     * @param integer $pk product id
     * @return Products model
     */
    public static function returnProduct($pk) {
        return static::find()->byPk($pk);
    }

    /**
     * 
     * @param string $code product code
     * @param string $name product name
     * @param string $helb_code HELB code
     * @param integer $active active: 1 - yes; 0 - no
     * @param string $oneOrAll one or all
     * @return Products model(s)
     */
    public static function searchProducts($code, $name, $helb_code, $active, $oneOrAll) {
        return static::find()->searchProducts($code, $name, $helb_code, $active, $oneOrAll);
    }
    
    /**
     * 
     * @return Products models
     */
    public static function allProducts() {
        return static::searchProducts(null, null, null, null, self::all);
    }

    /**
     * 
     * @param string $code product code
     * @return Products model
     */
    public static function byCode($code) {
        return static::searchProducts($code, null, null, null, self::one);
    }

    /**
     * 
     * @param string $name product name
     * @return Products models
     */
    public static function forName($name) {
        return static::searchProducts(null, $name, null, null, self::all);
    }

    /**
     * 
     * @param string $helb_code HELB code
     * @return Products models
     */
    public static function forHELBCode($helb_code) {
        return static::searchProducts(null, null, $helb_code, null, self::all);
    }

    /**
     * 
     * @param string $attribute attribute of [[$this]]
     * @param string $value value of [[$attribute]]
     * @param integer $id product id
     * @return Products model
     */
    public static function distinctNaming($attribute, $value, $id) {
        return static::find()->distinctNaming($attribute, $value, $id);
    }

    /**
     * 
     * @return Products model
     */
    public static function newProduct() {
        $model = new Products;

        $model->active = self::active_no;

        $model->created_by = Yii::$app->user->identity->username;

        return $model;
    }

    /**
     * 
     * @param integer $id product id
     * @return Products model
     */
    public static function productToLoad($id) {
        return is_object($model = static::returnProduct($id)) ? $model : static::newProduct();
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
     * @return array actives
     */
    public static function actives() {
        return [
            self::active_yes => 'Active',
            self::active_no => 'Inactive'
        ];
    }

    /**
     * 
     * @return array acceptable image types
     */
    public static function extensions() {
        return ['jpg', 'jpeg', 'png', 'gif'];
    }

}
