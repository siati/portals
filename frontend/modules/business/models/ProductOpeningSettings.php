<?php

namespace frontend\modules\business\models;

use Yii;
use common\models\StaticMethods;
use common\models\LmBaseEnums;

/**
 * This is the model class for table "{{%product_opening_settings}}".
 *
 * @property integer $id
 * @property integer $application
 * @property string $setting
 * @property string $name
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
     *
     * @var array possible option values
     */
    public $values;

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
            [['application', 'setting', 'name', 'value', 'created_by'], 'required'],
            [['application', 'value', 'active'], 'integer'],
            [['application'], 'positiveValue'],
            [['remark'], 'string'],
            [['created_at', 'modified_at'], 'safe'],
            [['created_by', 'modified_by'], 'string', 'max' => 20],
            [['setting', 'name'], 'string', 'max' => 40],
            [['setting', 'value'], 'subsequentCheck'],
        ];
    }

    /**
     * 
     * @return boolean true - subsequent check passed
     */
    public function subsequentCheck() {
        if ($this->setting == ProductSettings::has_subsequent &&
                is_object($application = ProductOpening::returnOpening($this->application)) &&
                $application->subsequent == LmBaseEnums::applicantType(LmBaseEnums::applicant_type_subsequent)->VALUE) {
            $this->addError('setting', 'This setting is not applicable on subsequent applications');
            $this->addError('value', 'This setting is not applicable on subsequent applications');

            return !$this->hasErrors('setting');
        }
        
        return true;
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
     * @param string $value setting value
     * @param integer $active active: 0 - false, true
     * @param string $remark setting remark
     * @param string $oneOrAll one or all
     * @return ProductOpeningSettings model(s)
     */
    public static function searchSettings($application, $setting, $name, $value, $active, $remark, $oneOrAll) {
        return static::find()->searchSettings($application, $setting, $name, $value, $active, $remark, $oneOrAll);
    }
    
    /**
     * 
     * @param integer $application product opening id
     * @return boolean true - has subsequent
     */
    public static function hasSubsequent($application) {
        return is_object(static::searchSettings($application, ProductSettings::has_subsequent, null, ProductSettings::yes, self::active, null, self::one));
    }
    
    /**
     * 
     * @param integer $application product opening id
     * @return boolean true - has appeal
     */
    public static function hasAppeal($application) {
        return is_object(static::searchSettings($application, ProductSettings::has_appeal, null, ProductSettings::yes, self::active, null, self::one));
    }
    
    /**
     * 
     * @param integer $application product opening id
     * @return boolean true - has bursary
     */
    public static function hasBursary($application) {
        return is_object(static::searchSettings($application, ProductSettings::has_bursary, null, ProductSettings::yes, self::active, null, self::one));
    }
    
    /**
     * 
     * @param integer $application product opening id
     * @return ProductOpeningSettings model
     */
    public static function employed($application) {
        return static::searchSettings($application, ProductSettings::employed, null, null, self::active, null, self::one);
    }
    
    /**
     * 
     * @param integer $application product opening id
     * @return array tuition, upkeep - true, false
     */
    public static function tuitionOrUpkeep($application) {
        $tuition_or_upkeep = static::searchSettings($application, ProductSettings::tuition_or_upkeep, null, null, self::active, null, self::one);
        
        return [
            ProductSettings::yes => is_object($tuition_or_upkeep) && in_array($tuition_or_upkeep->value, [ProductSettings::yes, ProductSettings::both]),
            ProductSettings::no => is_object($tuition_or_upkeep) && in_array($tuition_or_upkeep->value, [ProductSettings::no, ProductSettings::both])
        ];
    }
    
    /**
     * 
     * @param integer $application product opening id
     * @return boolean true - has society narration
     */
    public static function hasSocietyNarration($application) {
        return is_object(static::searchSettings($application, ProductSettings::has_society_narration, null, ProductSettings::yes, self::active, null, self::one));
    }
    
    /**
     * 
     * @param integer $application product opening id
     * @return boolean true - has financial literacy
     */
    public static function hasFInancialLiteracy($application) {
        return is_object(static::searchSettings($application, ProductSettings::has_financial_literacy, null, ProductSettings::yes, self::active, null, self::one));
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
        return static::searchSettings($application, $setting, null, null, $active, null, $oneOrAll);
    }

    /**
     * 
     * @param integer $application product opening id
     * @param string $setting setting
     * @return ProductOpeningSettings model
     */
    public static function byApplicationAndSetting($application, $setting) {
        return static::searchSettings($application, $setting, null, null, null, null, self::one);
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
        return is_object($model = static::returnSetting($id)) || (!empty($application) && !empty($setting) && is_object($model = static::byApplicationAndSetting($application, $setting))) ? $model : static::newSetting($application, $setting);
    }

    /**
     * 
     * @param integer $application product opening id
     * @return ProductOpeningSettings models
     */
    public static function settingsToLoad($application) {
        foreach (ProductSettings::theSettings() as $setting => $detail) {
            $settingModel = static::settingToLoad(null, $application, $setting);

            if ($settingModel->isNewRecord && $detail[ProductSettings::active] == ProductSettings::yes) {
                $settingModel->value = $detail[ProductSettings::default_value];
                $settingModel->remark = $settingModel->name = $detail[ProductSettings::name];
            }

            $settingModel->values = $detail[ProductSettings::values];

            if (!$settingModel->isNewRecord || $detail[ProductSettings::active] == ProductSettings::yes)
                $settings[] = $settingModel;
        }

        return empty($settings) ? [] : $settings;
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

        return $this->subsequentCheck() ? $this->save() : true;
    }

}
