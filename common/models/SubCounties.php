<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%sub_counties}}".
 *
 * @property integer $id
 * @property integer $county
 * @property string $name
 */
class SubCounties extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%sub_counties}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['county', 'name'], 'required'],
            [['county'], 'integer'],
            [['name'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'county' => Yii::t('app', 'County'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @inheritdoc
     * @return \common\activeQueries\SubCountiesQuery the active query used by this AR class.
     */
    public static function find() {
        return new \common\activeQueries\SubCountiesQuery(get_called_class());
    }
    
    /**
     * 
     * @param integer $pk sub-county id
     * @return SubCounties model
     */
    public static function returnSubcounty($pk) {
        return static::find()->byPk($pk);
    }

    /**
     * 
     * @return SubCounties models
     */
    public static function allSubcounties() {
        return static::find()->allSubcounties();
    }

    /**
     * 
     * @param integer $county county id
     * @return SubCounties models
     */
    public static function subcountiesForCounty($county) {
        return static::find()->subcountiesForCounty($county);
    }

}
