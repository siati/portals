<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%counties}}".
 *
 * @property integer $id
 * @property string $name
 */
class Counties extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%counties}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @inheritdoc
     * @return \common\activeQueries\CountiesQuery the active query used by this AR class.
     */
    public static function find() {
        return new \common\activeQueries\CountiesQuery(get_called_class());
    }

    /**
     * 
     * @param integer $pk county id
     * @return Counties model
     */
    public static function returnCounty($pk) {
        return static::findByPk($pk);
    }
    
    /**
     * 
     * @return Counties models
     */
    public static function allCounties() {
        return static::find()->allCounties();
    }

}
