<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%lm_employers}}".
 *
 * @property string $ACCOUNTNUM
 * @property string $NAME
 */
class LmEmployers extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%lm_employers}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['ACCOUNTNUM', 'NAME'], 'required'],
            [['ACCOUNTNUM'], 'string', 'max' => 15],
            [['NAME'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ACCOUNTNUM' => Yii::t('app', 'Accountnum'),
            'NAME' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @inheritdoc
     * @return \common\activeQueries\LmEmployersQuery the active query used by this AR class.
     */
    public static function find() {
        return new \common\activeQueries\LmEmployersQuery(get_called_class());
    }
    
    /**
     * 
     * @param integer $ACCOUNTNUM accountnum
     * @param string $NAME name
     * @param string $oneOrAll one or all
     * @return LmEmployers model(s)
     */
    public static function searchEmployers($ACCOUNTNUM, $NAME, $oneOrAll) {
        return static::find()->searchEmployers($ACCOUNTNUM, $NAME, $oneOrAll);
    }

}
