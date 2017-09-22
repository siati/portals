<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%constituencies}}".
 *
 * @property integer $id
 * @property integer $county
 * @property string $name
 */
class Constituencies extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%constituencies}}';
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
     * @return \common\activeQueries\ConstituenciesQuery the active query used by this AR class.
     */
    public static function find() {
        return new \common\activeQueries\ConstituenciesQuery(get_called_class());
    }

    /**
     * 
     * @param integer $pk constituency id
     * @return Constituencies model
     */
    public static function returnConstituency($pk) {
        return static::findByPk($pk);
    }

    /**
     * 
     * @return Constituencies models
     */
    public static function allConstituencies() {
        return static::find()->allConstituencies();
    }

    /**
     * 
     * @param integer $county county id
     * @return Constituencies models
     */
    public static function constituenciesForCounty($county) {
        return static::find()->constituenciesForCounty($county);
    }

}
