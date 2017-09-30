<?php

namespace frontend\modules\client\modules\student\activeQueries;
use frontend\modules\client\modules\student\models\ApplicantsFamilyExpenses;
/**
 * This is the ActiveQuery class for [[\frontend\modules\client\modules\student\models\ApplicantsFamilyExpenses]].
 *
 * @see \frontend\modules\client\modules\student\models\ApplicantsFamilyExpenses
 */
class ApplicantsFamilyExpensesQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\models\ApplicantsFamilyExpenses[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\models\ApplicantsFamilyExpenses|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }
    
    /**
     * 
     * @param integer $applicant applicant id
     * @param integer $expense_type expense type
     * @param string $oneOrAll one or all
     * @return ApplicantsFamilyExpenses ActiveRecord[s]
     */
    public function searchExpenses($applicant, $expense_type, $oneOrAll) {
        return $this->where("applicant = '$applicant'" . (is_number($expense_type) ? " && expense_type = '$expense_type'" : ''))->orderBy('expense_type asc')->$oneOrAll();
    }

}
