<?php

namespace frontend\modules\client\modules\student\activeQueries;

use frontend\modules\client\modules\student\models\ApplicantsEmployment;

/**
 * This is the ActiveQuery class for [[\frontend\modules\client\modules\student\models\ApplicantsEmployment]].
 *
 * @see \frontend\modules\client\modules\student\models\ApplicantsEmployment
 */
class ApplicantsEmploymentQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\models\ApplicantsEmployment[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\models\ApplicantsEmployment|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @param intger $applicant applicant id
     * @param integer $employment_terms employment terms
     * @param intger $county integer
     * @param string $oneOrAll one or all
     * @return ApplicantsEmployment ActiveRecord(s)
     */
    public function searchEmployment($applicant, $employment_terms, $county, $oneOrAll) {
        return $this->where(
                        'id > 0' .
                        (empty($applicant) ? '' : " && applicant = '$applicant'") .
                        (is_numeric($employment_terms) ? " && employment_terms = '$employment_terms'" : '') .
                        (empty($county) ? '' : " && county = '$county'")
                )->$oneOrAll();
    }

}
