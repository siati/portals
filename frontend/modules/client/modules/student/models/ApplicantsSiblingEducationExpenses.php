<?php

namespace frontend\modules\client\modules\student\models;

use Yii;

/**
 * This is the model class for table "{{%applicants_sibling_education_expenses}}".
 *
 * @property integer $id
 * @property integer $applicant
 * @property integer $birth_cert_no
 * @property integer $id_no
 * @property string $fname
 * @property string $mname
 * @property string $lname
 * @property integer $study_level
 * @property integer $institution_type
 * @property string $institution_name
 * @property integer $annual_fees
 * @property string $helb_beneficiary
 * @property string $created_by
 * @property string $created_at
 * @property string $modified_by
 * @property string $modified_at
 */
class ApplicantsSiblingEducationExpenses extends \yii\db\ActiveRecord {

    const helb_beneficiary_yes = 1;
    const helb_beneficiary_no = 0;


    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%applicants_sibling_education_expenses}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['applicant', 'birth_cert_no', 'id_no', 'fname', 'lname', 'study_level', 'institution_type', 'institution_name', 'annual_fees', 'created_by'], 'required'],
            [['applicant', 'birth_cert_no', 'id_no', 'study_level', 'institution_type', 'annual_fees'], 'integer'],
            [['helb_beneficiary'], 'string'],
            [['created_at', 'modified_at'], 'safe'],
            [['fname', 'mname', 'lname', 'created_by', 'modified_by'], 'string', 'max' => 20],
            [['institution_name'], 'string', 'max' => 40],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'applicant' => Yii::t('app', 'Applicant'),
            'birth_cert_no' => Yii::t('app', 'Birth Certificate No.'),
            'id_no' => Yii::t('app', 'National ID. No.'),
            'fname' => Yii::t('app', 'First Name'),
            'mname' => Yii::t('app', 'Middle Name'),
            'lname' => Yii::t('app', 'Last Name'),
            'study_level' => Yii::t('app', 'Study Level'),
            'institution_type' => Yii::t('app', 'Institution Type'),
            'institution_name' => Yii::t('app', 'Institution Name'),
            'annual_fees' => Yii::t('app', 'Annual Fees'),
            'helb_beneficiary' => Yii::t('app', 'HELB Beneficiary'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\activeQueries\ApplicantsSiblingEducationExpensesQuery the active query used by this AR class.
     */
    public static function find() {
        return new \frontend\modules\client\modules\student\activeQueries\ApplicantsSiblingEducationExpensesQuery(get_called_class());
    }
    
    /**
     * 
     * @param integer $pk expense id
     * @return ApplicantsSiblingEducationExpenses model
     */
    public static function returnExpense($pk) {
        return static::find()->byPk($pk);
    }
    
    /**
     * 
     * @param intger $applicant applicant id
     * @param integer $birth_cert_no sibling birth certificate no
     * @param integer $id_no sibling id no
     * @param string $oneOrAll one or all
     * @return ApplicantsSiblingEducationExpenses model[s]
     */
    public static function searchExpenses($applicant, $birth_cert_no, $id_no, $oneOrAll) {
        return static::find()->searchExpenses($applicant, $birth_cert_no, $id_no, $oneOrAll);
    }
    
    /**
     * 
     * @param intger $applicant applicant id
     * @return ApplicantsSiblingEducationExpenses models
     */
    public static function expensesForApplicant($applicant) {
        return static::searchExpenses($applicant, null, null, 'all');
    }
    
    /**
     * 
     * @param intger $applicant applicant id
     * @param integer $birth_cert_no sibling birth certificate no
     * @param integer $id_no sibling id no
     * @return ApplicantsSiblingEducationExpenses model
     */
    public static function newExpense($applicant, $birth_cert_no, $id_no) {
        $model = new ApplicantsSiblingEducationExpenses;
        
        $model->applicant = $applicant;
        
        $model->birth_cert_no = $birth_cert_no;
        
        $model->id_no = $id_no;
        
        $model->helb_beneficiary = self::helb_beneficiary_no;
        
        $model->created_by = Yii::$app->user->identity->username;
        
        return $model;
    }
    
    /**
     * 
     * @param integer $id expense id
     * @param intger $applicant applicant id
     * @param integer $birth_cert_no sibling birth certificate no
     * @param integer $id_no sibling id no
     * @return ApplicantsSiblingEducationExpenses model
     */
    public static function expenseToLoad($id, $applicant, $birth_cert_no, $id_no) {
        return is_object($model = static::returnExpense($id)) ? $model : static::newExpense($applicant, $birth_cert_no, $id_no);
    }
    
    /**
     * 
     * @param integer $applicant applicant id
     * @return ApplicantsSiblingEducationExpenses model
     */
    public static function expensesToLoad($applicant) {
        return static::expensesForApplicant($applicant);
    }
    
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
