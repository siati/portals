<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%postal_codes}}".
 *
 * @property integer $id
 * @property string $town
 * @property string $code
 */
class PostalCodes extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%postal_codes}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['town', 'code'], 'required'],
            [['town'], 'string', 'max' => 40],
            [['code'], 'string', 'max' => 5],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'town' => Yii::t('app', 'Town'),
            'code' => Yii::t('app', 'Postal Code'),
        ];
    }

    /**
     * @inheritdoc
     * @return \common\activeQueries\PostalCodesQuery the active query used by this AR class.
     */
    public static function find() {
        return new \common\activeQueries\PostalCodesQuery(get_called_class());
    }

    /**
     * 
     * @param integer $pk code id
     * @return PostalCodes model
     */
    public static function returnCode($pk) {
        return static::findByPk($pk);
    }
    
    /**
     * 
     * @return PostalCodes models
     */
    public static function allCodes() {
        return static::find()->allCodes();
    }

}
