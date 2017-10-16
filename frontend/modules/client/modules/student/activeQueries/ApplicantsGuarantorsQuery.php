<?php

namespace frontend\modules\client\modules\student\activeQueries;
use frontend\modules\client\modules\student\models\ApplicantsGuarantors;

/**
 * This is the ActiveQuery class for [[\frontend\modules\client\modules\student\models\ApplicantsGuarantors]].
 *
 * @see \frontend\modules\client\modules\student\models\ApplicantsGuarantors
 */
class ApplicantsGuarantorsQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\models\ApplicantsGuarantors[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\models\ApplicantsGuarantors|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param string $gender gender
     * @param integer $id_no national id. no.
     * @param integer $phone phone number
     * @param string $email email
     * @param string $kra_pin kra pin
     * @return ApplicantsGuarantors ActiveRecords
     */
    public function searchGuarantors($applicant, $gender, $id_no, $phone, $email, $kra_pin) {
        return $this->where(
                        'id > 0' .
                        (empty($applicant) ? '' : " && applicant = '$applicant'") .
                        (is_numeric($gender) ? " && gender = '$gender'" : '') .
                        (empty($id_no) ? '' : " && id_no = '$id_no'") .
                        (empty($phone) ? '' : " && phone like '%$phone%'") .
                        (empty($email) ? '' : " && email like '%$email%'") .
                        (empty($kra_pin) ? '' : " && kra_pin = '$kra_pin'")
                )->all();
    }
    
    /**
     * 
     * @param string $attribute attribute of guarantor
     * @param string $value value of [[$attribute]]
     * @param integer $id guarantor id
     * @return ApplicantsGuarantors ActiveRecord
     */
    public function distinctDetails($attribute, $value, $id) {
        return $this->where("$attribute = '$value' && id != '$id'")->one();
    }
    
    /**
     * 
     * @param string $attribute attribute of User model
     * @param string $value value of [[$attribute]]
     * @param integer $applicant applicant's id
     * @return ApplicantsGuarantors ActiveRecord
     */
    public function siblingNotGuarantor($attribute, $value, $applicant) {
        return $this->where("applicant = '$applicant' && $attribute = '$value'")->one();
    }

}
