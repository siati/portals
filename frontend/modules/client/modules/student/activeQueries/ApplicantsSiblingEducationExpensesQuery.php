<?php

namespace frontend\modules\client\modules\student\activeQueries;

use frontend\modules\client\modules\student\models\ApplicantsSiblingEducationExpenses;

/**
 * This is the ActiveQuery class for [[\frontend\modules\client\modules\student\models\ApplicantsSiblingEducationExpenses]].
 *
 * @see \frontend\modules\client\modules\student\models\ApplicantsSiblingEducationExpenses
 */
class ApplicantsSiblingEducationExpensesQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\models\ApplicantsSiblingEducationExpenses[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\models\ApplicantsSiblingEducationExpenses|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @param intger $applicant applicant id
     * @param integer $birth_cert_no sibling birth certificate no
     * @param integer $id_no sibling id no
     * @param string $oneOrAll one or all
     * @return ApplicantsSiblingEducationExpenses ActiveRecord[s]
     */
    public function searchExpenses($applicant, $birth_cert_no, $id_no, $oneOrAll) {
        return $this->where(
                        "applicant  = '$applicant'" .
                        (empty($birth_cert_no) ? '' : " && birth_cert_no = '$birth_cert_no'") .
                        (empty($id_no) ? '' : " && id_no = '$id_no'")
                )->$oneOrAll();
    }
    
    /**
     * 
     * @param string $attribute attribute of guarantor
     * @param string $value value of [[$attribute]]
     * @param integer $id expense id
     * @return ApplicantsSiblingEducationExpenses ActiveRecord
     */
    public function distinctDetails($attribute, $value, $id) {
        return $this->where("$attribute = '$value' && id != '$id'")->one();
    }
    
    /**
     * 
     * @param string $attribute attribute of User model
     * @param string $value value of [[$attribute]]
     * @param integer $applicant applicant's id
     * @return ApplicantsSiblingEducationExpenses ActiveRecord
     */
    public function notOwnSenior($attribute, $value, $applicant) {
        return $this->where("applicant = '$applicant' && $attribute = '$value'")->one();
    }

}
