<?php

namespace frontend\modules\client\modules\student\models;

use Yii;
use common\models\LmBaseEnums;
use common\models\StaticMethods;

/**
 * This is the model class for table "{{%applicants_family_expenses}}".
 *
 * @property integer $id
 * @property integer $applicant
 * @property integer $expense_type
 * @property integer $amount
 * @property string $created_by
 * @property string $created_at
 * @property string $modified_by
 * @property string $modified_at
 */
class ApplicantsFamilyExpenses extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%applicants_family_expenses}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['applicant', 'expense_type', 'amount', 'created_by'], 'required'],
            [['applicant', 'expense_type', 'amount'], 'integer'],
            ['amount', 'sanitizeString'],
            ['amount', 'string', 'min' => 4, 'max' => 6],
            ['amount', 'positiveValue'],
            [['created_at', 'modified_at'], 'safe'],
            [['created_by', 'modified_by'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'applicant' => Yii::t('app', 'Applicant'),
            'expense_type' => Yii::t('app', 'Expense Type'),
            'amount' => Yii::t('app', is_object($expenseType = LmBaseEnums::byNameAndValue(LmBaseEnums::expense_type, $this->expense_type)) ? $expenseType->LABEL : 'Amount'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\activeQueries\ApplicantsFamilyExpensesQuery the active query used by this AR class.
     */
    public static function find() {
        return new \frontend\modules\client\modules\student\activeQueries\ApplicantsFamilyExpensesQuery(get_called_class());
    }

    /**
     * 
     * @param integer $pk expense id
     * @return ApplicantsFamilyExpenses model
     */
    public static function returnExpense($pk) {
        return static::find()->byPk($pk);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param integer $expense_type expense type
     * @param string $oneOrAll one or all
     * @return ApplicantsFamilyExpenses model[s]
     */
    public static function searchExpenses($applicant, $expense_type, $oneOrAll) {
        return static::find()->searchExpenses($applicant, $expense_type, $oneOrAll);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @return ApplicantsFamilyExpenses models
     */
    public static function expensesForApplicant($applicant) {
        return static::searchExpenses($applicant, null, 'all');
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param integer $expense_type expense type
     * @return ApplicantsFamilyExpenses model
     */
    public static function newExpense($applicant, $expense_type) {
        $model = new ApplicantsFamilyExpenses;

        $model->applicant = $applicant;

        $model->expense_type = $expense_type;

        $model->created_by = Yii::$app->user->identity->username;

        return $model;
    }

    /**
     * 
     * @param integer $id expense id
     * @param integer $applicant applicant id
     * @param integer $expense_type expense type
     * @return ApplicantsFamilyExpenses model
     */
    public static function expenseToLoad($id, $applicant, $expense_type) {
        return is_object($model = static::returnExpense($id)) || is_object($model = static::searchExpenses($applicant, $expense_type, 'one')) ? $model : static::newExpense($applicant, $expense_type);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @return ApplicantsFamilyExpenses models
     */
    public static function expensesToLoad($applicant) {
        foreach (LmBaseEnums::expenseTypes() as $expense_type => $label)
            $models[$expense_type] = static::expenseToLoad(null, $applicant, $expense_type);

        return empty($models) ? [] : $models;
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
