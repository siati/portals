<?php

namespace frontend\modules\business\models;

use Yii;

/**
 * This is the model class for table "{{%product_opening_settings}}".
 *
 * @property integer $id
 * @property integer $application
 * @property string $setting
 * @property string $name
 * @property string $label
 * @property string $value
 * @property string $active
 * @property string $remark
 * @property string $created_by
 * @property string $created_at
 * @property string $modified_by
 * @property string $modified_at
 */
class ProductOpeningSettings extends \yii\db\ActiveRecord {
    
    const active = 1;
    const not_active = 0;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%product_opening_settings}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['application', 'setting', 'name', 'label', 'value', 'created_by'], 'required'],
            [['application'], 'integer'],
            [['active', 'remark'], 'string'],
            [['created_at', 'modified_at'], 'safe'],
            [['setting', 'value', 'created_by', 'modified_by'], 'string', 'max' => 20],
            [['name', 'label'], 'string', 'max' => 40],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'application' => Yii::t('app', 'Application'),
            'setting' => Yii::t('app', 'Parameter'),
            'name' => Yii::t('app', 'Name'),
            'label' => Yii::t('app', 'Label'),
            'value' => Yii::t('app', 'Value'),
            'active' => Yii::t('app', 'Active'),
            'remark' => Yii::t('app', 'Remark'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\business\activeQueries\ProductOpeningSettingsQuery the active query used by this AR class.
     */
    public static function find() {
        return new \frontend\modules\business\activeQueries\ProductOpeningSettingsQuery(get_called_class());
    }
    
    /**
     * 
     * @param integer $pk setting id
     * @return ProductOpeningSettings model
     */
    public static function returnSetting($pk) {
        return static::find()->byPk($pk);
    }
    
    /**
     * 
     * @param integer $application product opening id
     * @param string $setting setting
     * @param string $name setting name
     * @param string $label setting label
     * @param string $value setting value
     * @param integer $active active: 0 - false, true
     * @param string $remark setting remark
     * @param string $oneOrAll one or all
     * @return ProductOpeningSettings model(s)
     */
    public static function searchSettings($application, $setting, $name, $label, $value, $active, $remark, $oneOrAll) {
        return static::find()->searchSettings($application, $setting, $name, $label, $value, $active, $remark, $oneOrAll);
    }
    
    /**
     * 
     * @param integer $application product opening id
     * @param string $setting setting
     * @param integer $active active: 0 - false, true
     * @param string $oneOrAll one or all
     * @return ProductOpeningSettings model(s)
     */
    public static function forApplicationSettingAndActive($application, $setting, $active, $oneOrAll) {
        return static::searchSettings($application, $setting, null, null, null, $active, null, $oneOrAll);
    }
    
    /**
     * 
     * @param integer $application product opening id
     * @param string $setting setting
     * @return ProductOpeningSettings model
     */
    public static function byApplicationAndSetting($application, $setting) {
        return static::searchSettings($application, $setting, null, null, null, null, null, self::one);
    }
    
    /**
     * 
     * @param integer $application product opening id
     * @param string $setting setting
     * @return ProductOpeningSettings model
     */
    public static function newSetting($application, $setting) {
        $model = new ProductOpeningSettings;
        
        $model->application = $application;
        $model->setting = $setting;
        $model->active = self::active;
        
        $model->created_by = Yii::$app->user->identity->username;
        
        return $model;
    }
    
    /**
     * 
     * @param integer $id setting id
     * @param integer $application product opening id
     * @param string $setting setting
     * @return ProductOpeningSettings model
     */
    public static function settingToLoad($id, $application, $setting) {
        return is_object($model = static::returnSetting($id)) || is_object($model = static::byApplicationAndSetting($application, $setting)) ? $model : static::newSetting($application, $setting);
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
