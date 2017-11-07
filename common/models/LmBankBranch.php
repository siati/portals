<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%lm_bank_branch}}".
 *
 * @property string $BANKCODE
 * @property string $BRANCHCODE
 * @property string $BRANCHNAME
 * @property integer $BRANCHTYPE
 * @property string $RECID
 */
class LmBankBranch extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%lm_bank_branch}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['BANKCODE', 'BRANCHCODE', 'BRANCHNAME', 'BRANCHTYPE', 'RECID'], 'required'],
            [['BRANCHTYPE', 'RECID'], 'integer'],
            [['BANKCODE'], 'string', 'max' => 5],
            [['BRANCHCODE'], 'string', 'max' => 20],
            [['BRANCHNAME'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'BANKCODE' => Yii::t('app', 'Bank Code'),
            'BRANCHCODE' => Yii::t('app', 'Branch Code'),
            'BRANCHNAME' => Yii::t('app', 'Branch Name'),
            'BRANCHTYPE' => Yii::t('app', 'Branch Type'),
            'RECID' => Yii::t('app', 'RECID'),
        ];
    }

    /**
     * @inheritdoc
     * @return \common\activeQueries\LmBankBranchQuery the active query used by this AR class.
     */
    public static function find() {
        return new \common\activeQueries\LmBankBranchQuery(get_called_class());
    }
    
    /**
     * 
     * @param integer $BANKCODE bank code
     * @param integer $BRANCHCODE branch code
     * @param string $oneOrAll one or all
     * @return LmBankBranch model(s)
     */
    public static function searchBranches($BANKCODE, $BRANCHCODE, $oneOrAll) {
        return static::find()->searchBranches($BANKCODE, $BRANCHCODE, $oneOrAll);
    }
    
    /**
     * 
     * @param integer $BANKCODE bank code
     * @param integer $BRANCHCODE branch code
     * @return LmBankBranch model
     */
    public static function byBankAndBranchCode($BANKCODE, $BRANCHCODE) {
        return static::find()->searchBranches($BANKCODE, $BRANCHCODE, self::one);
    }

}
