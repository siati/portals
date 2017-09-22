<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%wards}}".
 *
 * @property integer $id
 * @property integer $constituency
 * @property string $name
 */
class Wards extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%wards}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['constituency', 'name'], 'required'],
            [['constituency'], 'integer'],
            [['name'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'constituency' => Yii::t('app', 'Constituency'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @inheritdoc
     * @return \common\activeQueries\WardsQuery the active query used by this AR class.
     */
    public static function find() {
        return new \common\activeQueries\WardsQuery(get_called_class());
    }

    /**
     * 
     * @param integer $pk ward id
     * @return Wards model
     */
    public static function returnConstituency($pk) {
        return static::findByPk($pk);
    }

    /**
     * 
     * @return Wards models
     */
    public static function allWards() {
        return static::find()->allWards();
    }

    /**
     * 
     * @param integer $constituency constituency id
     * @return Wards models
     */
    public static function wardsForConstituency($constituency) {
        return static::find()->wardsForConstituency($constituency);
    }

}
