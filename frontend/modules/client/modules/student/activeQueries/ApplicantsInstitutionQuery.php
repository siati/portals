<?php

namespace frontend\modules\client\modules\student\activeQueries;

use frontend\modules\client\modules\student\models\ApplicantsInstitution;
/**
 * This is the ActiveQuery class for [[\frontend\modules\client\modules\student\models\ApplicantsInstitution]].
 *
 * @see \frontend\modules\client\modules\student\models\ApplicantsInstitution
 */
class ApplicantsInstitutionQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\models\ApplicantsInstitution[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\models\ApplicantsInstitution|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }
    
    /**
     * 
     * @param integer $applicant applicant id
     * @return ApplicantsInstitution ActiveRecord
     */
    public function forApplicant($applicant) {
        return $this->where("applicant = '$applicant'")->one();
    }

}
