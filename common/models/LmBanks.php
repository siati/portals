<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%lm_banks}}".
 *
 * @property string $RECID
 * @property string $NAME
 * @property string $BANKCODE
 * @property integer $CHECKDIGITS
 * @property integer $MINIMUMACCOUNTDIGITS
 * @property integer $MAXIMUMACCOUNTDIGITS
 * @property integer $DISPLAYONLINE
 */
class LmBanks extends \yii\db\ActiveRecord {
    
    const offline = '0';
    const online = '1';
    const check_digits_no = '0';
    const check_digits_yes = '1';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%lm_banks}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['RECID', 'NAME', 'BANKCODE', 'CHECKDIGITS', 'MINIMUMACCOUNTDIGITS', 'MAXIMUMACCOUNTDIGITS', 'DISPLAYONLINE'], 'required'],
            [['RECID', 'CHECKDIGITS', 'MINIMUMACCOUNTDIGITS', 'MAXIMUMACCOUNTDIGITS', 'DISPLAYONLINE'], 'integer'],
            [['NAME'], 'string', 'max' => 30],
            [['BANKCODE'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'RECID' => Yii::t('app', 'RECID'),
            'NAME' => Yii::t('app', 'Bank Nme'),
            'BANKCODE' => Yii::t('app', 'Bank Code'),
            'CHECKDIGITS' => Yii::t('app', 'Check Digits'),
            'MINIMUMACCOUNTDIGITS' => Yii::t('app', 'Minimum Account Digits'),
            'MAXIMUMACCOUNTDIGITS' => Yii::t('app', 'Maximum Account Digits'),
            'DISPLAYONLINE' => Yii::t('app', 'Display Online'),
        ];
    }

    /**
     * @inheritdoc
     * @return \common\activeQueries\LmBanksQuery the active query used by this AR class.
     */
    public static function find() {
        return new \common\activeQueries\LmBanksQuery(get_called_class());
    }
    
    /**
     * 
     * @param integer $BANKCODE bank code
     * @param integer $DISPLAYONLINE display online
     * @param string $oneOrAll one or all
     * @return LmBanks model(s)
     */
    public static function searchBanks($BANKCODE, $DISPLAYONLINE, $oneOrAll) {
        return static::find()->searchBanks($BANKCODE, $DISPLAYONLINE, $oneOrAll);
    }
    
    /**
     * 
     * @param integer $BANKCODE bank code
     * @return LmBanks model
     */
    public static function byBankCode($BANKCODE) {
        return static::searchBanks($BANKCODE, null, self::one);
    }

}
